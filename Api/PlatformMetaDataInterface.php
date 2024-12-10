<?php

namespace FusionLab\Core\Api;

interface PlatformMetaDataInterface
{
    /**
     * Get PHP version.
     *
     * @return string
     */
    public function getPhpVersion(): string;

    /**
     * Set PHP version.
     *
     * @param string $phpVersion
     * @return string
     */
    public function setPhpVersion(string $phpVersion): string;

    /**
     * Get MySQL version.
     *
     * @return string
     */
    public function getMysqlVersion(): string;

    /**
     * Set MySQL version.
     *
     * @param string $mysqlVersion
     * @return string
     */
    public function setMysqlVersion(string $mysqlVersion): string;

    /**
     * Get platform name.
     *
     * @return string
     */
    public function getPlatform(): string;

    /**
     * Set platform name.
     *
     * @param string $platform
     * @return string
     */
    public function setPlatform(string $platform): string;

    /**
     * Get platform version.
     *
     * @return string
     */
    public function getVersion(): string;

    /**
     * Set platform version.
     *
     * @param string $version
     * @return string
     */
    public function setVersion(string $version): string;

    /**
     * Get platform URL.
     *
     * @return string
     */
    public function getUrl(): string;

    /**
     * Set platform URL.
     *
     * @param string $url
     * @return string
     */
    public function setUrl(string $url): string;

    /**
     * Get modules data.
     *
     * @return \FusionLab\Core\Api\ModulesDataInterface[]
     */
    public function getModules(): array;

    /**
     * Set modules data.
     *
     * @param \FusionLab\Core\Api\ModulesDataInterface[] $modules
     * @return \FusionLab\Core\Api\ModulesDataInterface[]
     */
    public function setModules(array $modules): array;

    /**
     * Get Refreshed Token.
     *
     * @param string $token
     * @return string
     */
    public function getRefreshedToken(string $token) : string;

    /**
     * Set Refreshed Token.
     *
     * @param string $token
     * @return string
     */
    public function setRefreshedToken(string $token) : string;

}
