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
