<?php

namespace FusionLab\Core\Model;

use FusionLab\Core\Api\Data\ModulesDataInterface;
use Magento\Framework\DataObject;

class Modules extends DataObject implements ModulesDataInterface
{

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->getData(self::NAME);
    }

    /**
     * @inheritDoc
     */
    public function setName(string $name): \FusionLab\Core\Api\Data\ModulesDataInterface
    {
        return $this->setData(self::NAME, $name);
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
    public function setVersion(string $version): \FusionLab\Core\Api\Data\ModulesDataInterface
    {
        return $this->setData(self::VERSION, $version);
    }

    /**
     * @inheritDoc
     */
    public function getStatus(): bool
    {
        return $this->getData(self::STATUS);
    }

    /**
     * @inheritDoc
     */
    public function setStatus(bool $status): \FusionLab\Core\Api\Data\ModulesDataInterface
    {
        return $this->setData(self::STATUS, $status);
    }
}
