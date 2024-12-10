<?php

namespace FusionLab\Core\Model;

use GuzzleHttp\Exception\GuzzleException;
use FusionLab\Core\Api\RegistrationInterface;
use GuzzleHttp\Client;
use Magento\Framework\Filesystem\DirectoryList;
use FusionLab\Core\Api\ApplicationInfoInterface;

/**
 * Class HttpPost
 * @package FusionLab\Core\Model
 */
class Registration implements RegistrationInterface
{
    private const Registration_ENDPOINT = 'http://warden.p83.localhost/api/register';

    /** @var Client */
    private Client $_client;

    /** @var DirectoryList */
    private DirectoryList $_directoryList;

    /** @var ApplicationInfoInterface */
    private ApplicationInfoInterface $_applicationInfo;


    /**
     * @param Client $client
     * @param DirectoryList $directoryList
     * @param ApplicationInfoInterface $applicationInfo
     */
    public function __construct(
        Client                   $client,
        DirectoryList            $directoryList,
        ApplicationInfoInterface $applicationInfo
    )
    {
        $this->_client = $client;
        $this->_directoryList = $directoryList;
        $this->_applicationInfo = $applicationInfo;
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
            $response = $this->_client->post(self::Registration_ENDPOINT, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'body' => json_encode([
                    'platform' => $this->_applicationInfo->getPlatform(),
                    'url' => $this->_applicationInfo->getApplicationUrl(),
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
     * @param $response
     * @return void
     */
    private function processRegistrationResponse($response): void
    {
        $uid = $response->getBody()->getContents() ?? null;

        if ($this->isValidUid($uid)) {
            $this->_applicationInfo->setToken($uid);
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
            $fileName = $this->_applicationInfo->generateToken() . '.txt';
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
     * @return bool
     */
    private function isRegistered(): bool
    {
        return !empty($this->_applicationInfo->getCurrentAuthToken());
    }

}
