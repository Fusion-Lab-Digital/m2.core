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

namespace FusionLab\Core\Api\Data;

interface PlatformMetaDataInterface
{

    const PHP_VERSION = 'php_version';
    const MYSQL_VERSION = 'mysql_version';
    const PLATFORM = 'platform';
    const VERSION = 'version';
    const URL = 'url';
    const MODULES = 'modules';
    const REFRESHED_TOKEN = 'refresh_token';

    /**
     * @return string
     */
    public function getPhpVersion(): string;

    /**
     * @param string $phpVersion
     * @return \FusionLab\Core\Api\Data\PlatformMetaDataInterface
     */
    public function setPhpVersion(string $phpVersion): \FusionLab\Core\Api\Data\PlatformMetaDataInterface;

    /**
     * @return string
     */
    public function getMysqlVersion(): string;

    /**
     * @param string $mysqlVersion
     * @return \FusionLab\Core\Api\Data\PlatformMetaDataInterface
     */
    public function setMysqlVersion(string $mysqlVersion): \FusionLab\Core\Api\Data\PlatformMetaDataInterface;

    /**
     * @return string
     */
    public function getPlatform(): string;

    /**
     * @param string $platform
     * @return \FusionLab\Core\Api\Data\PlatformMetaDataInterface
     */
    public function setPlatform(string $platform): \FusionLab\Core\Api\Data\PlatformMetaDataInterface;

    /**
     * @return string
     */
    public function getVersion(): string;

    /**
     * @param string $version
     * @return \FusionLab\Core\Api\Data\PlatformMetaDataInterface
     */
    public function setVersion(string $version): \FusionLab\Core\Api\Data\PlatformMetaDataInterface;

    /**
     * @return string
     */
    public function getUrl(): string;

    /**
     * @param string $url
     * @return \FusionLab\Core\Api\Data\PlatformMetaDataInterface
     */
    public function setUrl(string $url): \FusionLab\Core\Api\Data\PlatformMetaDataInterface;

    /**
     * @return \FusionLab\Core\Api\Data\ModulesDataInterface[]
     */
    public function getModules(): array;

    /**
     * @param \FusionLab\Core\Api\Data\ModulesDataInterface[] $modules
     * @return \FusionLab\Core\Api\Data\PlatformMetaDataInterface
     */
    public function setModules(array $modules): \FusionLab\Core\Api\Data\PlatformMetaDataInterface;

    /**
     * @return string|null
     */
    public function getRefreshedToken(): ?string;

    /**
     * @param string $token
     * @return \FusionLab\Core\Api\Data\PlatformMetaDataInterface
     */
    public function setRefreshedToken(string $token): \FusionLab\Core\Api\Data\PlatformMetaDataInterface;
}
