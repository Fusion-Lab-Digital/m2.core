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
namespace FusionLab\Core\Test\Unit\Model;

use Exception;
use FusionLab\Core\Api\ApplicationInfoInterface;
use FusionLab\Core\Model\AppRegistration;
use GuzzleHttp\Client;
use Magento\Framework\App\Filesystem\DirectoryList;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class AppRegistrationTest extends TestCase
{

    private AppRegistration $appRegistration;

    private MockObject|Client $clientMock;

    private MockObject|DirectoryList $directoryListMock;

    private MockObject|ApplicationInfoInterface $applicationInfoMock;

    private MockObject|LoggerInterface $loggerMock;

    protected function setUp(): void
    {
        // Mock dependencies.
        $this->clientMock = $this->createMock(Client::class);
        $this->directoryListMock = $this->createMock(DirectoryList::class);
        $this->applicationInfoMock = $this->createMock(ApplicationInfoInterface::class);
        $this->loggerMock = $this->createMock(LoggerInterface::class);

        // Initialize AppRegistration with mocked dependencies.
        $this->appRegistration = new AppRegistration(
            $this->clientMock,
            $this->directoryListMock,
            $this->applicationInfoMock,
            $this->loggerMock
        );
    }

    public function testMakeSignatureCreatesDirectoryAndFile(): void
    {
        // Mock DirectoryList to return a specific path.
        $this->directoryListMock
            ->method('getPath')
            ->with('pub')
            ->willReturn('/var/www/html/pub');

        // Simulate the base directory.
        $basePath = '/var/www/html/pub/fusionlab';

        // Ensure the directory does not already exist before the test.
        if (!is_dir($basePath)) {
            mkdir($basePath, 0755, true); // Create the directory only if it doesn't exist.
        }

        // Simulate no existing files in the directory.
        $existingFiles = glob($basePath . '/*.txt');
        foreach ($existingFiles as $file) {
            unlink($file); // Remove all files for a clean slate.
        }

        // Simulate file creation behavior.
        $fileName = 'testfile.txt';
        $absoluteFilePath = $basePath . DIRECTORY_SEPARATOR . $fileName;
        file_put_contents($absoluteFilePath, '');

        // Call makeSignature and verify the output.
        $result = $this->appRegistration->makeSignature();
        $this->assertStringContainsString('fusionlab/', $result);
    }

    public function testMakeSignatureHandlesExistingFiles(): void
    {
        // Mock DirectoryList to return a specific path.
        $this->directoryListMock
            ->method('getPath')
            ->with('pub')
            ->willReturn('/var/www/html/pub');

        // Simulate an existing directory and file.
        $basePath = '/var/www/html/pub/fusionlab';
        $existingFilePath = $basePath . '/existing.txt';

        // Simulate directory already existing.
        if (!is_dir($basePath)) {
            mkdir($basePath, 0755, true); // Create the directory only if it doesn't exist.
        }

        // Create an existing file.
        file_put_contents($existingFilePath, '');

        // Call makeSignature and verify the output.
        $result = $this->appRegistration->makeSignature();

        // Ensure it returns the existing file's path.
        $this->assertEquals('fusionlab/existing.txt', $result);
    }

    public function testMakeSignatureLogsErrorOnException(): void
    {
        // Force DirectoryList to throw an exception.
        $this->directoryListMock
            ->method('getPath')
            ->with('pub')
            ->will($this->throwException(new Exception('Test exception')));

        // Expect logger to capture the error.
        $this->loggerMock
            ->expects($this->once())
            ->method('error')
            ->with(
                $this->stringContains('Test exception'),
                $this->anything()
            );

        // Call makeSignature and verify the output.
        $result = $this->appRegistration->makeSignature();
        $this->assertEmpty($result); // Should return an empty string on failure.
    }
}
