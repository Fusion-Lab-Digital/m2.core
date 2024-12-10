<?php

namespace FusionLab\Core\Model;

use FusionLab\Core\Api\ApplicationInfoInterface;
use FusionLab\Core\Api\ModulesDataInterface;
use FusionLab\Core\Api\PlatformMetaDataInterface;
use Magento\Framework\Module\ModuleListInterface;
use Magento\Framework\App\ProductMetadataInterface;
use FusionLab\Core\Api\Data\ModulesDataFactory;
use FusionLab\Core\Api\Data\PlatformMetaDataFactory;
use Magento\Framework\App\ResourceConnection;

class ApplicationInfo implements ApplicationInfoInterface
{
    /** @var ModuleListInterface */
    private ModuleListInterface $_moduleList;

    /** @var ProductMetadataInterface */
    private ProductMetadataInterface $_productMetaDataInterace;

    /** @var ModulesDataFactory */
    private ModulesDataFactory $_modulesDataFactory;

    /** @var PlatformMetaDataFactory */
    private PlatformMetaDataFactory $_platformMetaDataFactory;

    /** @var ResourceConnection */
    private ResourceConnection $_resourceConnection;


    /**
     * @param ModuleListInterface $moduleList
     * @param ProductMetadataInterface $productMetaDataInterface
     * @param ModulesDataFactory $modulesDataFactory
     * @param PlatformMetaDataFactory $platformMetaDataFactory
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(
        ModuleListInterface      $moduleList,
        ProductMetadataInterface $productMetaDataInterface,
        ModulesDataFactory       $modulesDataFactory,
        PlatformMetaDataFactory  $platformMetaDataFactory,
        ResourceConnection       $resourceConnection
    )
    {
        $this->_moduleList = $moduleList;
        $this->_productMetaDataInterace = $productMetaDataInterface;
        $this->_modulesDataFactory = $modulesDataFactory;
        $this->_platformMetaDataFactory = $platformMetaDataFactory;
        $this->_resourceConnection = $resourceConnection;
    }

    /**
     * @return PlatformMetaDataInterface
     */
    public function getApplicationInfo(): PlatformMetaDataInterface
    {
        /** @var PlatformMetaDataInterface $platformMetaData */
        $platformMetaData = $this->_platformMetaDataFactory->create();
        $platformMetaData->setPlatform('Magento2');
        $platformMetaData->setPhpVersion(PHP_VERSION);
        $platformMetaData->setMysqlVersion($this->getMySQLVersion());
        $platformMetaData->setVersion($this->_productMetaDataInterace->getVersion());
        $platformMetaData->setModules($this->getFusionLabModules());
        $platformMetaData->setUrl($this->getApplicationUrl());
        //dd($platformMetaData);
        return $platformMetaData;
    }

    /**
     * Get Website URL (Secure or fallback to Unsecure URL)
     *
     * @return string
     */
    public function getApplicationUrl(): string
    {
        // Get the connection object
        $connection = $this->_resourceConnection->getConnection();

        // Try to get the secure URL first (HTTPS)
        $select = $connection->select()
            ->from('core_config_data', 'value')
            ->where('path = ?', 'web/secure/base_url');

        $secureUrl = $connection->fetchOne($select);

        // If secure URL is not set, fallback to the unsecure URL (HTTP)
        if (!$secureUrl) {
            $select = $connection->select()
                ->from('core_config_data', 'value')
                ->where('path = ?', 'web/unsecure/base_url');

            $secureUrl = $connection->fetchOne($select);
        }

        return $secureUrl;
    }

    /**
     * @return ModulesDataInterface[]
     */
    private function getFusionLabModules(): array
    {
        $modules = $this->_moduleList->getAll();
        $modulesData = [];


        foreach ($modules as $fusionLabModule) {
            if (!$this->isFusionLabModule($fusionLabModule['name'])) {
                continue;
            }

            /** @var ModulesDataInterface $module */
            $module = $this->_modulesDataFactory->create();
            $module->setName($fusionLabModule['name']);
            $module->setVersion($fusionLabModule['setup_version'] ?? '');
            $module->setStatus(true); // Assume all modules are enabled
            $modulesData[] = $module;
        }
        return $modulesData;
    }

    /**
     * @return string
     */
    private function getMySQLVersion(): string
    {
        $connection = $this->_resourceConnection->getConnection();
        return $connection->fetchOne("SELECT VERSION()");
    }

    /**
     * @param string $moduleName
     * @return bool
     */
    private function isFusionLabModule(string $moduleName): bool
    {
        if (!str_contains($moduleName, 'Fusion')) {
            return false;
        }
        return true;
    }


}
