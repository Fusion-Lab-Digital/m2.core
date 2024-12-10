<?php

namespace FusionLab\Core\Model;

use FusionLab\Core\Api\Data\PlatformMetaDataInterface;
use Magento\Framework\DataObject;

class PlatformMetaData extends DataObject implements PlatformMetaDataInterface
{

    /**
     * @inheritDoc
     */
    public function getPhpVersion(): string
    {
        return $this->getData(self::PHP_VERSION);
    }

    /**
     * @inheritDoc
     */
    public function setPhpVersion(string $phpVersion): \FusionLab\Core\Api\Data\PlatformMetaDataInterface
    {
        return $this->setData(self::PHP_VERSION, $phpVersion);
    }

    /**
     * @inheritDoc
     */
    public function getMysqlVersion(): string
    {
        return $this->getData(self::MYSQL_VERSION);
    }

    /**
     * @inheritDoc
     */
    public function setMysqlVersion(string $mysqlVersion): \FusionLab\Core\Api\Data\PlatformMetaDataInterface
    {
        return $this->setData(self::MYSQL_VERSION, $mysqlVersion);
    }

    /**
     * @inheritDoc
     */
    public function getPlatform(): string
    {
        return $this->getData(self::PLATFORM);
    }

    /**
     * @inheritDoc
     */
    public function setPlatform(string $platform): \FusionLab\Core\Api\Data\PlatformMetaDataInterface
    {
        return $this->setData(self::PLATFORM, $platform);
    }

    /**
     * @inheritDoc
     */
    public function getVersion(): string
    {
        return $this->getData(self::VERSION);
    }

    /**
     * @inheritDoc
     */
    public function setVersion(string $version): \FusionLab\Core\Api\Data\PlatformMetaDataInterface
    {
        return $this->setData(self::VERSION, $version);
    }

    /**
     * @inheritDoc
     */
    public function getUrl(): string
    {
        return $this->getData(self::URL);
    }

    /**
     * @inheritDoc
     */
    public function setUrl(string $url): \FusionLab\Core\Api\Data\PlatformMetaDataInterface
    {
        return $this->setData(self::URL, $url);
    }

    /**
     * @inheritDoc
     */
    public function getModules(): array
    {
        return $this->getData(self::MODULES);
    }

    /**
     * @inheritDoc
     */
    public function setModules(array $modules): \FusionLab\Core\Api\Data\PlatformMetaDataInterface
    {
        return $this->setData(self::MODULES, $modules);
    }

    /**
     * @inheritDoc
     */
    public function getRefreshedToken(): ?string
    {
        return $this->getData(self::REFRESHED_TOKEN);
    }

    /**
     * @inheritDoc
     */
    public function setRefreshedToken(string $token): \FusionLab\Core\Api\Data\PlatformMetaDataInterface
    {
        return $this->setData(self::REFRESHED_TOKEN, $token);
    }
}
