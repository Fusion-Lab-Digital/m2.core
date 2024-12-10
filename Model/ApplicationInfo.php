<?php

namespace FusionLab\Core\Model;

use FusionLab\Core\Api\ApplicationInfoInterface;
use FusionLab\Core\Api\Data\ModulesDataInterfaceFactory;
use FusionLab\Core\Api\Data\ModulesDataInterface;
use FusionLab\Core\Api\Data\PlatformMetaDataInterfaceFactory;
use FusionLab\Core\Api\Data\PlatformMetaDataInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\App\ProductMetadataInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Encryption\Encryptor;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Module\Manager;
use Magento\Framework\Module\ModuleListInterface;
use Magento\Framework\Webapi\Rest\Request;
use Magento\Store\Model\StoreManagerInterface;

class ApplicationInfo implements ApplicationInfoInterface
{

    private ModuleListInterface $_moduleList;

    private ProductMetadataInterface $_productMetaDataInterace;

    private ModulesDataInterfaceFactory $_modulesDataFactory;

    private PlatformMetaDataInterfaceFactory $_platformMetaDataFactory;

    private AdapterInterface $_resourceConnection;

    private Manager $_moduleManager;

    private WriterInterface $_configWriter;

    private Encryptor $_encryptor;

    private Request $_request;

    private StoreManagerInterface $storeManager;

    /**
     * @param ModuleListInterface $moduleList
     * @param ProductMetadataInterface $productMetaDataInterface
     * @param ModulesDataInterfaceFactory $modulesDataFactory
     * @param PlatformMetaDataInterfaceFactory $platformMetaDataFactory
     * @param ResourceConnection $resourceConnection
     * @param Manager $moduleManager
     * @param WriterInterface $configWriter
     * @param Encryptor $encryptor
     * @param Request $request
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ModuleListInterface              $moduleList,
        ProductMetadataInterface         $productMetaDataInterface,
        ModulesDataInterfaceFactory      $modulesDataFactory,
        PlatformMetaDataInterfaceFactory $platformMetaDataFactory,
        ResourceConnection               $resourceConnection,
        Manager                          $moduleManager,
        WriterInterface                  $configWriter,
        Encryptor                        $encryptor,
        Request                          $request,
        StoreManagerInterface         $storeManager

    )
    {
        $this->_moduleList = $moduleList;
        $this->_productMetaDataInterace = $productMetaDataInterface;
        $this->_modulesDataFactory = $modulesDataFactory;
        $this->_platformMetaDataFactory = $platformMetaDataFactory;
        $this->_resourceConnection = $resourceConnection->getConnection();
        $this->_moduleManager = $moduleManager;
        $this->_configWriter = $configWriter;
        $this->_encryptor = $encryptor;
        $this->_request = $request;
        $this->storeManager = $storeManager;
    }

    /**
     * @return PlatformMetaDataInterface
     * @throws LocalizedException
     */
    public function getApplicationInfo(): PlatformMetaDataInterface
    {
        $this->authenticateRequest();
        $newToken = $this->generateToken();
        $platformMetaData = $this->getPlatformMetaData($newToken);
        $this->setToken($newToken);
        return $platformMetaData;
    }


    /**
     * @param $newToken
     * @return PlatformMetaDataInterface
     */
    public function getPlatformMetaData($newToken = null): PlatformMetaDataInterface
    {
        /** @var PlatformMetaDataInterface $platformMetaData */
        $platformMetaData = $this->_platformMetaDataFactory->create();
        $platformMetaData->setPlatform(self::PLATFORM);
        $platformMetaData->setPhpVersion(PHP_VERSION);
        $platformMetaData->setMysqlVersion($this->getMySQLVersion());
        $platformMetaData->setVersion($this->_productMetaDataInterace->getVersion());
        $platformMetaData->setModules($this->getFusionLabModules());
        $platformMetaData->setUrl($this->getApplicationUrl());
        if($newToken){
            $platformMetaData->setRefreshedToken($newToken);
        }
        return $platformMetaData;
    }


    /**
     * @throws LocalizedException
     */
    private function authenticateRequest(): void
    {
        $externalToken = $this->_request->getHeader('x-fusionlab-secret');
        if (!$externalToken) {
            throw new LocalizedException(__('Missing authentication token in the request headers.'));
        }

        try {
            $internalToken = $this->_encryptor->decrypt($this->getCurrentAuthToken());
        } catch (\Exception $e) {
            throw new LocalizedException(__('Invalid Token.'));
        }
        if ($internalToken !== $externalToken) {
            throw new LocalizedException(__('Authentication failed: invalid token.'));
        }
    }

    /**
     * @return string
     */
    public function getCurrentAuthToken(): string
    {
        $connection = $this->_resourceConnection;
        $select = $connection->select()
            ->from($connection->getTableName('core_config_data'), ['value'])
            ->where('path  = ?  ', ApplicationInfo::UID_PATH);

        return $connection->fetchOne($select);
    }

    /**
     * @return string|null
     */
    public function getApplicationUrl(): ?string
    {
        try {
            return $this->storeManager->getStore()->getBaseUrl();
        } catch (NoSuchEntityException $e) {

        }
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
            $module->setStatus($this->getModuleStatus($fusionLabModule['name']));
            $modulesData[] = $module;
        }
        return $modulesData;
    }

    /**
     * @param string $moduleName
     * @return bool
     */
    public function getModuleStatus(string $moduleName): bool
    {
        return $this->_moduleManager->isEnabled($moduleName);
    }

    /**
     * @return string
     */
    private function getMySQLVersion(): string
    {
        return $this->_resourceConnection->fetchOne("SELECT VERSION()");
    }

    /**
     * @param string $moduleName
     * @return bool
     */
    private function isFusionLabModule(string $moduleName): bool
    {
        return str_contains($moduleName, 'FusionLab_');
    }


    /**
     * @param string $token
     * @return void
     */
    public function setToken(string $token): void
    {
        $this->_configWriter->save(self::UID_PATH, $this->_encryptor->encrypt($token));
    }

    /**
     * @return string
     */
    public function generateToken(): string
    {
        return uniqid();
    }

}
