<?php

namespace FusionLab\Core\Api;

interface ModulesDataInterface
{
    /**
     * Get module name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Set module name.
     *
     * @param string $name
     * @return string
     */
    public function setName(string $name): string;

    /**
     * Get module version.
     *
     * @return string
     */
    public function getVersion(): string;

    /**
     * Set module version.
     *
     * @param string $version
     * @return string
     */
    public function setVersion(string $version): string;

    /**
     * Get module status.
     *
     * @return bool
     */
    public function getStatus(): bool;

    /**
     * Set module status.
     *
     * @param bool $status
     * @return bool
     */
    public function setStatus(bool $status): bool;
}
