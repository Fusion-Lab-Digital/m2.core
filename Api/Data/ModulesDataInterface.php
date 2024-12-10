<?php

namespace FusionLab\Core\Api\Data;

interface ModulesDataInterface
{

    const NAME = 'name';
    const VERSION = 'version';
    const STATUS = 'status';

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param string $name
     * @return \FusionLab\Core\Api\Data\ModulesDataInterface
     */
    public function setName(string $name): \FusionLab\Core\Api\Data\ModulesDataInterface;

    /**
     * @return string
     */
    public function getVersion(): string;

    /**
     * @param string $version
     * @return \FusionLab\Core\Api\Data\ModulesDataInterface
     */
    public function setVersion(string $version): \FusionLab\Core\Api\Data\ModulesDataInterface;

    /**
     * @return bool
     */
    public function getStatus(): bool;

    /**
     * @param bool $status
     * @return \FusionLab\Core\Api\Data\ModulesDataInterface
     */
    public function setStatus(bool $status): \FusionLab\Core\Api\Data\ModulesDataInterface;
}
