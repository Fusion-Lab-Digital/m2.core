<?php

namespace FusionLab\Core\Api;

interface ApplicationInfoInterface
{
    /**
     * @return \FusionLab\Core\Api\PlatformMetaDataInterface
     */
    public function getApplicationInfo(): \FusionLab\Core\Api\PlatformMetaDataInterface;
}
