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
namespace FusionLab\Core\Block\Adminhtml;

use FusionLab\Core\Api\ApplicationInfoInterface;
use FusionLab\Core\Api\Data\ModulesDataInterface;
use FusionLab\Core\Api\Data\PlatformMetaDataInterface;
use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\View\Helper\SecureHtmlRenderer;

class InstalledExtensions extends Field
{

    protected $_template = 'FusionLab_Core::installed_extensions.phtml';

    private ApplicationInfoInterface $applicationInfo;

    /**
     * @param Context $context
     * @param ApplicationInfoInterface $applicationInfo
     * @param array $data
     * @param SecureHtmlRenderer|null $secureRenderer
     */
    public function __construct(
        Context $context,
        ApplicationInfoInterface $applicationInfo,
        array $data = [],
        ?SecureHtmlRenderer $secureRenderer = null
    ) {
        $this->applicationInfo = $applicationInfo;
        parent::__construct($context, $data, $secureRenderer);
    }

    /**
     * @return ModulesDataInterface[]
     */
    public function getModules():array
    {
        return $this->applicationInfo->getPlatformMetaData()->getModules();
    }

    /**
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element): string
    {
        return $this->toHtml();
    }
}
