<?php

namespace FusionLab\Core\Model;

use GuzzleHttp\Exception\GuzzleException;
use Magento\Framework\App\Config\ScopeConfigInterface;
use FusionLab\Core\Api\BeaconInterface;
use GuzzleHttp\Client;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\Encryption\Encryptor;


/**
 * Class HttpPost
 * @package FusionLab\Core\Model
 */
class Beacon implements BeaconInterface
{
    private const BEACON_ENDPOINT = 'http://warden.p83.localhost/api/register';
    private const UID_PATH = 'fusionlab_settings/general/application_uid';

    /** @var Client */
    private Client $_client;

    /** @var ScopeConfigInterface */
    private ScopeConfigInterface $_scopeConfig;

    /** @var DirectoryList */
    private DirectoryList $_directoryList;

    /** @var WriterInterface */
    private WriterInterface $_configWriter;

    /** @var AdapterInterface */
    private AdapterInterface $_connection;

    /** @var Encryptor  */
    private Encryptor $_encryptor;


    /**
     * @param Client $client
     * @param ScopeConfigInterface $config
     * @param DirectoryList $directoryList
     * @param WriterInterface $configWriter
     * @param ResourceConnection $connection
     * @param Encryptor $encryptor
     */
    public function __construct(
        Client               $client,
        ScopeConfigInterface $config,
        DirectoryList        $directoryList,
        WriterInterface      $configWriter,
        ResourceConnection   $connection,
        Encryptor            $encryptor
    )
    {
        $this->_client = $client;
        $this->_scopeConfig = $config;
        $this->_directoryList = $directoryList;
        $this->_configWriter = $configWriter;
        $this->_connection = $connection->getConnection();
        $this->_encryptor = $encryptor;
    }


    /**
     * @return void
     */
    public function register(): void
    {
        if ($this->isRegistered()) {
            //return;
        }

        try {
            $response = $this->_client->post(self::BEACON_ENDPOINT, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'body' => json_encode([
                    'platform' => $this->getPlatform(),
                    'url' => $this->getApplicationUrl(),
                    'verification' => $this->initVerification(),
                ]),
                'timeout' => 5.0,
            ]);

            $this->processRegistrationResponse($response);
        } catch (GuzzleException $e) {
            var_dump($e->getMessage());
        }
    }

    /**
     * @param $uid
     * @return void
     */
    private function storeUid($uid): void
    {
        $this->_configWriter->save(self::UID_PATH, $this->_encryptor->encrypt($uid));
    }

    /**
     * @param $response
     * @return void
     */
    private function processRegistrationResponse($response): void
    {
        $uid = $response->getBody()->getContents() ?? null;

        if ($this->isValidUid($uid)) {
            $this->storeUid($uid);
        }
    }

    /**
     * @param $uid
     * @return bool
     */
    private function isValidUid($uid): bool
    {
        if (isset($uid) && strlen($uid) === 36) {
            return true;
        }
        return false;
    }


    /**
     * @return string
     */
    public function initVerification(): string
    {
        $verificationFilePath = '';
        try {
            // Define the base directory (absolute path)
            $basePath = $this->_directoryList->getPath('pub') . '/fusionlab';

            // Ensure the directory exists
            if (!is_dir($basePath)) {
                mkdir($basePath, 0755, true);
            }

            // Generate the file path
            $fileName = $this->generateRandomString(16) . '.txt';
            $absoluteFilePath = $basePath . DIRECTORY_SEPARATOR . $fileName;

            // Write an empty file
            file_put_contents($absoluteFilePath, '');

            // Prepare the relative path without the leading slash
            $verificationFilePath = 'fusionlab/' . $fileName;

            // Debug output (optional, for testing)
            //var_dump($verificationFilePath);

        } catch (\Exception $e) {
            // Handle exceptions
            var_dump('Error: ' . $e->getMessage());
        }

        return $verificationFilePath;
    }


    /**
     * Generate a random string of a given length.
     * Ensures randomness even if both `random_bytes` and `random_int` fail.
     *
     * @param int $length
     * @return string
     */
    private function generateRandomString(int $length): string
    {
        try {
            // Divide by 2 because bin2hex doubles the length
            return bin2hex(random_bytes($length / 2));
        } catch (\Exception $exception) {
            // Fallback to alternative random generator
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            try {
                for ($i = 0; $i < $length; $i++) {
                    $randomString .= $characters[random_int(0, $charactersLength - 1)];
                }
            } catch (\Exception $e) {
                // Fallback random generator
                for ($i = 0; $i < $length; $i++) {
                    $randomString .= $characters[mt_rand(0, $charactersLength - 1)];
                }
            }
            return $randomString;
        }
    }


    /**
     * @return string
     */
    private function getPlatform(): string
    {
        return 'Magento2';
    }


    /**
     * @return string
     */
    private function getApplicationUrl(): string
    {
        return $this->_scopeConfig->getValue('web/secure/base_url') ?? $this->_scopeConfig->getValue('web/unsecure/base_url');
    }

    /**
     * @return bool
     */
    private function isRegistered(): bool
    {
        $select = $this->_connection->select()
            ->from($this->_connection->getTableName('core_config_data'), ['value'])
            ->where('path  = ?  ', self::UID_PATH);

        return !empty($this->_connection->fetchOne($select));
    }

}
