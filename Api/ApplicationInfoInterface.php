<?php

namespace FusionLab\Core\Api;

interface ApplicationInfoInterface
{

    const PLATFORM = 'Magento2';
    const UID_PATH = 'fusionlab_settings/general/application_uid';

    /**
     * @return \FusionLab\Core\Api\Data\PlatformMetaDataInterface
     */
    public function getApplicationInfo(): Data\PlatformMetaDataInterface;
}
