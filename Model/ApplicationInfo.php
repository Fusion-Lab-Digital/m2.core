<?php

namespace FusionLab\Core\Model;

use FusionLab\Core\Api\ApplicationInfoInterface;
use FusionLab\Core\Api\ModulesDataInterface;
use FusionLab\Core\Api\PlatformMetaDataInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Encryption\Encryptor;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Module\ModuleListInterface;
use Magento\Framework\App\ProductMetadataInterface;
use FusionLab\Core\Api\Data\ModulesDataFactory;
use FusionLab\Core\Api\Data\PlatformMetaDataFactory;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Module\Manager;
use Ramsey\Uuid\Uuid;
use Magento\Framework\Webapi\Rest\Request;

class ApplicationInfo implements ApplicationInfoInterface
{
    public const UID_PATH = 'fusionlab_settings/general/application_uid';

    /** @var ModuleListInterface */
    private ModuleListInterface $_moduleList;

    /** @var ProductMetadataInterface */
    private ProductMetadataInterface $_productMetaDataInterace;

    /** @var ModulesDataFactory */
    private ModulesDataFactory $_modulesDataFactory;

    /** @var PlatformMetaDataFactory */
    private PlatformMetaDataFactory $_platformMetaDataFactory;

    /** @var AdapterInterface */
    private AdapterInterface $_resourceConnection;

    /** @var Manager */
    private Manager $_moduleManager;

    /** @var WriterInterface */
    private WriterInterface $_configWriter;

    /** @var Encryptor */
    private Encryptor $_encryptor;

    /** @var Request */
    private Request $_request;

    /**
     * @param ModuleListInterface $moduleList
     * @param ProductMetadataInterface $productMetaDataInterface
     * @param ModulesDataFactory $modulesDataFactory
     * @param PlatformMetaDataFactory $platformMetaDataFactory
     * @param ResourceConnection $resourceConnection
     * @param Manager $moduleManager
     * @param WriterInterface $configWriter
     * @param Encryptor $encryptor
     * @param Request $request
     */
    public function __construct(
        ModuleListInterface      $moduleList,
        ProductMetadataInterface $productMetaDataInterface,
        ModulesDataFactory       $modulesDataFactory,
        PlatformMetaDataFactory  $platformMetaDataFactory,
        ResourceConnection       $resourceConnection,
        Manager                  $moduleManager,
        WriterInterface          $configWriter,
        Encryptor                $encryptor,
        Request                  $request

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
    }

    /**
     * @return AdapterInterface
     */
    public function getConnection(): AdapterInterface
    {
        return $this->_resourceConnection;
    }


    /**
     * @return PlatformMetaDataInterface
     * @throws LocalizedException
     */
    public function getApplicationInfo(): PlatformMetaDataInterface
    {
        $this->authenticateRequest();

        $newToken = $this->generateToken();

        /** @var PlatformMetaDataInterface $platformMetaData */
        $platformMetaData = $this->_platformMetaDataFactory->create();
        $platformMetaData->setPlatform('Magento2');
        $platformMetaData->setPhpVersion(PHP_VERSION);
        $platformMetaData->setMysqlVersion($this->getMySQLVersion());
        $platformMetaData->setVersion($this->_productMetaDataInterace->getVersion());
        $platformMetaData->setModules($this->getFusionLabModules());
        $platformMetaData->setUrl($this->getApplicationUrl());
        $platformMetaData->setRefreshedToken($newToken);

        //save the new token before reply
        $this->setToken($newToken);

        return $platformMetaData;
    }


    /**
     * @throws LocalizedException
     */
    private function authenticateRequest(): void
    {
        $externalToken = $this->_request->getHeader('x-fusionlab-secret');

        $internalToken = $this->decryptToken($this->getCurrentAuthToken());
        if (!$externalToken) {
            throw new LocalizedException(__('Missing authentication token in the request headers.'));
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
     * @return string
     */
    public function getPlatform(): string
    {
        return 'Magento2';
    }

    /**
     * Get Website URL (Secure or fallback to Unsecure URL)
     *
     * @return string
     */
    public function getApplicationUrl(): string
    {
        $connection = $this->_resourceConnection;

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
            $module->setStatus($this->getModuleStatus($fusionLabModule['name']));
            $modulesData[] = $module;
        }
        return $modulesData;
    }

    /**
     * @param $moduleName
     * @return bool
     */
    public function getModuleStatus($moduleName): bool
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
        if (!str_contains($moduleName, 'Fusion')) {
            return false;
        }
        return true;
    }


    /**
     * @param string $token
     * @return void
     */
    public function setToken(string $token): void
    {
        $uuid = $this->_encryptor->encrypt($token);
        $this->_configWriter->save(self::UID_PATH, $uuid);
    }

    /**
     * @param $token
     * @return string
     */
    public function decryptToken($token): string
    {
        try {
            return $this->_encryptor->decrypt($token);
        } catch (\Exception $e) {

        }
        return '';
    }

    /**
     * @return string
     */
    public function generateToken(): string
    {
        $uuid = Uuid::uuid4();
        return $uuid->toString();
    }


}
