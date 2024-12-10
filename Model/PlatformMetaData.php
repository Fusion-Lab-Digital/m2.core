<?php

namespace FusionLab\Core\Model;

use FusionLab\Core\Api\PlatformMetaDataInterface;

/**
 * Class PlatformMetaData
 *
 * This class implements the PlatformMetaDataInterface and is used to represent
 * platform metadata, including PHP and MySQL versions, platform details, and related modules.
 *
 * @package FusionLab\Core\Api\Data
 */
class PlatformMetaData implements PlatformMetaDataInterface
{
    /**
     * @var string PHP version
     */
    private $phpVersion;

    /**
     * @var string MySQL version
     */
    private $mysqlVersion;

    /**
     * @var string Platform name
     */
    private $platform;

    /**
     * @var string Platform version
     */
    private $version;

    /**
     * @var string URL of the platform
     */
    private $url;

    /**
     * @var array List of modules
     */
    private $modules = [];

    /**
     * @var string refreshed Token
     */
    private $refreshedToken;

    /**
     * Get the PHP version
     *
     * @return string PHP version
     */
    public function getPhpVersion(): string
    {
        return $this->phpVersion;
    }

    /**
     * Set the PHP version
     *
     * @param string $phpVersion The PHP version to set
     * @return string The set PHP version
     */
    public function setPhpVersion(string $phpVersion): string
    {
        $this->phpVersion = $phpVersion;
        return $this->phpVersion;
    }

    /**
     * Get the MySQL version
     *
     * @return string MySQL version
     */
    public function getMysqlVersion(): string
    {
        return $this->mysqlVersion;
    }

    /**
     * Set the MySQL version
     *
     * @param string $mysqlVersion The MySQL version to set
     * @return string The set MySQL version
     */
    public function setMysqlVersion(string $mysqlVersion): string
    {
        $this->mysqlVersion = $mysqlVersion;
        return $this->mysqlVersion;
    }

    /**
     * Get the platform name
     *
     * @return string Platform name
     */
    public function getPlatform(): string
    {
        return $this->platform;
    }

    /**
     * Set the platform name
     *
     * @param string $platform The platform name to set
     * @return string The set platform name
     */
    public function setPlatform(string $platform): string
    {
        $this->platform = $platform;
        return $this->platform;
    }

    /**
     * Get the platform version
     *
     * @return string Platform version
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * Set the platform version
     *
     * @param string $version The platform version to set
     * @return string The set platform version
     */
    public function setVersion(string $version): string
    {
        $this->version = $version;
        return $this->version;
    }

    /**
     * Get the URL associated with the platform
     *
     * @return string The platform's URL
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Set the URL associated with the platform
     *
     * @param string $url The URL to set
     * @return string The set URL
     */
    public function setUrl(string $url): string
    {
        $this->url = $url;
        return $this->url;
    }

    /**
     * Get the list of modules
     *
     * @return array The list of modules
     */
    public function getModules(): array
    {
        return $this->modules;
    }

    /**
     * Set the list of modules
     *
     * @param array $modules The modules to set
     * @return array The set modules
     */
    public function setModules(array $modules): array
    {
        $this->modules = $modules;
        return $this->modules;
    }

    /**
     * Get Refreshed Token.
     *
     * @param string $token
     * @return string
     */
    public function getRefreshedToken(string $token): string
    {
       return $this->refreshedToken;
    }

    /**
     * Set Refreshed Token.
     *
     * @param string $token
     * @return string
     */
    public function setRefreshedToken(string $token): string
    {
        $this->refreshedToken = $token;
        return $this->refreshedToken;
    }
}
