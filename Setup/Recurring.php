<?php

namespace FusionLab\Core\Setup;

use FusionLab\Core\Model\AppRegistration;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class Recurring implements InstallSchemaInterface
{
    private AppRegistration $appRegistration;

    public function __construct(AppRegistration $registration)
    {
        $this->appRegistration = $registration;
    }

    /**
     * @inheritdoc
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context): void
    {
        $this->appRegistration->register();
    }
}
