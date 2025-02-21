<?php
/**
 * Copyright (c) 2025 Fusion Lab G.P
 * Website: https://fusionlab.gr
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace FusionLab\Core\Model;

use Exception;
use FusionLab\Core\Api\ApplicationInfoInterface;
use FusionLab\Core\Api\RegistrationInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Magento\Framework\Filesystem\DirectoryList;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class AppRegistration implements RegistrationInterface
{
     const REGISTRATION_ENDPOINT = 'https://warden.fusionlab.gr/api/register';

    private Client $_client;

    private DirectoryList $_directoryList;

    private ApplicationInfoInterface $_applicationInfo;

    private LoggerInterface $logger;

    private ConfigProvider $configProvider;

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
        LoggerInterface          $logger,
        ConfigProvider           $configProvider
    ) {
        $this->_client = $client;
        $this->_directoryList = $directoryList;
        $this->_applicationInfo = $applicationInfo;
        $this->logger = $logger;
        $this->configProvider = $configProvider;
    }

    /**
     * @return void
     */
    public function register(): void
    {
        if (!$this->configProvider->isInstallationTrackingEnabled()) {
            return;
        }
        try {
            $response = $this->_client->post(
                self::REGISTRATION_ENDPOINT,
                [
                    'body' => json_encode(
                        [
                            'platform' => ApplicationInfoInterface::PLATFORM,
                            'url' => $this->_applicationInfo->getApplicationUrl(),
                            'verification' => $this->makeSignature(),
                        ]
                    ),
                    'connect_timeout' => 5.0,
                    'headers' => [
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                    ],
                    'timeout' => 5.0,
                ]
            );

            $this->processRegistrationResponse($response);
        } catch (GuzzleException $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
        }//end try
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

            // Check if the directory exists, if not, create it.
            if (!is_dir($basePath)) {
                mkdir($basePath, 0755, true);
            }

            // Check if a file already exists in the directory.
            $existingFiles = glob($basePath . '/*.txt');
            if (!empty($existingFiles)) {
                // Return the first existing file's relative path.
                $existingFilePath = basename($existingFiles[0]);
                return 'fusionlab/' . $existingFilePath;
            }

            // If no file exists, create a new one.
            $fileName = uniqid() . '.txt';
            $absoluteFilePath = $basePath . DIRECTORY_SEPARATOR . $fileName;
            file_put_contents($absoluteFilePath, '');
            $verificationFilePath = 'fusionlab/' . $fileName;
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
        }//end try

        return $verificationFilePath;
    }
}
