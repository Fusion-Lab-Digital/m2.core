<?php

namespace FusionLab\Core\Model;

use GuzzleHttp\Exception\GuzzleException;
use FusionLab\Core\Api\RegistrationInterface;
use GuzzleHttp\Client;
use Magento\Framework\Filesystem\DirectoryList;
use FusionLab\Core\Api\ApplicationInfoInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class AppRegistration implements RegistrationInterface
{
//    const REGISTRATION_ENDPOINT = 'https://warden.fusionlab.gr/api/register';
    const REGISTRATION_ENDPOINT = 'http://warden.p83.localhost/api/register';

    private Client $_client;
    private DirectoryList $_directoryList;
    private ApplicationInfoInterface $_applicationInfo;
    private LoggerInterface $logger;

    /**
     * @param Client $client
     * @param DirectoryList $directoryList
     * @param ApplicationInfoInterface $applicationInfo
     * @param LoggerInterface $logger
     */
    public function __construct(
        Client                   $client,
        DirectoryList            $directoryList,
        ApplicationInfoInterface $applicationInfo,
        LoggerInterface           $logger
    )
    {
        $this->_client = $client;
        $this->_directoryList = $directoryList;
        $this->_applicationInfo = $applicationInfo;
        $this->logger = $logger;
    }

    /**
     * @return void
     */
    public function register(): void
    {
        try {
            $response = $this->_client->post(self::REGISTRATION_ENDPOINT, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'body' => json_encode([
                    'platform' => ApplicationInfoInterface::PLATFORM,
                    'url' => $this->_applicationInfo->getApplicationUrl(),
                    'verification' => $this->makeSignature(),
                ]),
                'timeout' => 5.0,
                'connect_timeout' => 5.0,
            ]);

            $this->processRegistrationResponse($response);
        } catch (GuzzleException $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
        }
    }

    /**
     * @param ResponseInterface $response
     * @return void
     */
    private function processRegistrationResponse(ResponseInterface $response): void
    {
        $uid = $response->getBody()->getContents();

        if ($this->isValidUid($uid)) {
            $this->_applicationInfo->setToken($uid);
        }
    }

    /**
     * @param string|null $uid
     * @return bool
     */
    private function isValidUid(?string $uid): bool
    {
        return is_string($uid) && strlen($uid) === 36;
    }


    /**
     * @return string
     */
    public function makeSignature(): string
    {
        $verificationFilePath = '';
        try {
            $basePath = $this->_directoryList->getPath('pub') . '/fusionlab';

            // Check if the directory exists, if not, create it
            if (!is_dir($basePath)) {
                mkdir($basePath, 0755, true);
            }

            // Check if a file already exists in the directory
            $existingFiles = glob($basePath . '/*.txt');
            if (!empty($existingFiles)) {
                // Return the first existing file's relative path
                $existingFilePath = basename($existingFiles[0]);
                return 'fusionlab/' . $existingFilePath;
            }

            // If no file exists, create a new one
            $fileName = uniqid() . '.txt';
            $absoluteFilePath = $basePath . DIRECTORY_SEPARATOR . $fileName;
            file_put_contents($absoluteFilePath, '');
            $verificationFilePath = 'fusionlab/' . $fileName;
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
        }

        return $verificationFilePath;
    }
}
