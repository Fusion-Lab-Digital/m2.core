<?php

namespace FusionLab\Core\Model;

use GuzzleHttp\Exception\GuzzleException;
use Magento\Framework\App\Config\ScopeConfigInterface;
use FusionLab\Core\Api\BeaconInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\App\Config\Storage\WriterInterface;


/**
 * Class HttpPost
 * @package FusionLab\Core\Model
 */
class Beacon implements BeaconInterface
{
    private const BEACON_ENDPOINT = 'http://warden.p83.localhost/';
    private const UID_PATH = 'fusionlab_settings/general/application_uid';

    /** @var Client */
    private Client $_client;
    /** @var ScopeConfigInterface */
    private ScopeConfigInterface $_scopeConfig;
    /** @var DirectoryList */
    private DirectoryList $_directoryList;
    /** @var WriterInterface */
    private WriterInterface $_configWriter;

    /**
     * @param Client $client
     * @param ScopeConfigInterface $config
     * @param DirectoryList $directoryList
     * @param WriterInterface $configWriter
     */
    public function __construct(
        Client               $client,
        ScopeConfigInterface $config,
        DirectoryList        $directoryList,
        WriterInterface      $configWriter
    )
    {
        $this->_client = $client;
        $this->_scopeConfig = $config;
        $this->_directoryList = $directoryList;
        $this->_configWriter = $configWriter;
    }


    /**
     * @return void
     */
    public function register(): void
    {
        if ($this->isRegistered()) {
            return;
        }

        try {
            $response = $this->_client->post(self::BEACON_ENDPOINT, [
                'form_params' => [
                    'platform' => $this->getPlatform(),
                    'url' => $this->getApplicationUrl(),
                    'filepath' => $this->initVerification(),
                ],
            ]);
            $this->processRegistrationResponse($response);
        } catch (GuzzleException $e) {

        }
    }

    /**
     * @param $uid
     * @return void
     */
    private function storeUid($uid): void
    {
        $this->_configWriter->save(self::UID_PATH, $uid);
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

            $path = $this->_directoryList->getPath('pub') . DIRECTORY_SEPARATOR . 'fusionlab';
            $verificationFilePath =
                $path . DIRECTORY_SEPARATOR .
                $this->generateRandomString(16) . DIRECTORY_SEPARATOR .
                $this->generateRandomString(16) . '.txt';
            file_put_contents($verificationFilePath, $this->generateRandomString(512));
        } catch (\Exception $e) {

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
        return 'magento2';
    }


    /**
     * @return string|null
     */
    private function getApplicationUrl(): ?string
    {
        return (string)$this->_scopeConfig->getValue('web/secure/base_url') ?? null;
    }

    /**
     * @return bool
     */
    private function isRegistered(): bool
    {
        return !empty($this->_scopeConfig->getValue(self::UID_PATH));
    }
}
