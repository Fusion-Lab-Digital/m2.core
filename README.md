<img align="center" width="250" height="100" src="https://fusionlab.gr/fusion-lab-logo-neg-cropped.svg"/>


# Fusion Lab - Fusion Lab Core Extension

## 📌 Overview
**FusionLab Core**, acts as the cornerstone for Fusion Lab extensions, it is required to support the seamless operation of other Fusion Lab modules.

## ⚡ Features
- Provides core functionalities for FusionLab modules.
- Acts as a dependency for other FusionLab extensions.

## 🛠️ Installation

### Install via Composer 2.x
We recommend to install this module via a compatible version of [Composer 2.x](https://getcomposer.org/download/) for your Magento 2 Installtion.

See your [Magento 2 Requirements here](https://experienceleague.adobe.com/en/docs/commerce-operations/installation-guide/system-requirements). 
```bash
composer require fusionlab/core
php bin/magento module:enable FusionLab_Core
php bin/magento setup:upgrade
php bin/magento s:d:c
php bin/magento s:s:d {Your Themes}
php bin/magento cache:flush
```

### Manual Installation (not recommended)
1. Download the module and extract it into `app/code/FusionLab/Core`
2. Run the following Magento CLI commands:
```bash
php bin/magento module:enable FusionLab_Core
php bin/magento setup:upgrade
php bin/magento s:d:c
php bin/magento s:s:d {Your Themes}
php bin/magento cache:flush
```

## Configuration
This module does not require configuration directly. It works as a dependency for other Fusion Lab modules.

## Tracking Information
This module collects **non-personal data** to improve compatibility and performance. The data collected includes:
- Domain name
- PHP version
- MySQL version
- Usage of Fusion Lab modules

### Disable Tracking
To disable tracking:
1. Go to **Stores > Configuration > FusionLab Core > Enable Tracking**.
2. Set the **Enable Tracking** option to **No**.

No personal data is collected, and this data is used solely for improving the software.

## 📄 License

This module is licensed under the Apache 2.0 License. See [LICENSE](LICENSE) for details.

## 📩 Support

For any issues, feature requests, or inquiries, please open an issue on [GitHub Issues](https://github.com/Fusion-Lab-Digital/m2.core/issues), contact us at [info@fusionlab.gr](info@fusionlab.gr), or visit our website at [fusionlab.gr](https://fusionlab.gr) for more information.
