<?php

namespace FusionLab\Core\Model;

use FusionLab\Core\Api\ModulesDataInterface;

class Modules implements \FusionLab\Core\Api\ModulesDataInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $version;

    /**
     * @var bool
     */
    private $status;

    /**
     * Get module name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set module name.
     *
     * @param string $name
     * @return string
     */
    public function setName(string $name): string
    {
        $this->name = $name;
        return $this->name;
    }

    /**
     * Get module version.
     *
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * Set module version.
     *
     * @param string $version
     * @return string
     */
    public function setVersion(string $version): string
    {
        $this->version = $version;
        return $this->version;
    }

    /**
     * Get module status.
     *
     * @return bool
     */
    public function getStatus(): bool
    {
        return $this->status;
    }

    /**
     * Set module status.
     *
     * @param bool $status
     * @return bool
     */
    public function setStatus(bool $status): bool
    {
        $this->status = $status;
        return $this->status;
    }
}
