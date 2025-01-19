# Magento 2 Module: FusionLab Core

## Introduction
**FusionLab Core**, acts as the cornerstone for Fusion Lab extensions, offering critical functionalities and tools that enable and support the seamless operation of other Fusion Lab modules.

## Features
- Provides core functionalities for FusionLab modules.
- Acts as a dependency for other FusionLab extensions.
- Includes reusable utilities and tools for Magento 2.

## Installation
### Via Composer
1. Run the following command to require the module:
   ```bash
   composer require fusionlab/core
   ```
2. Enable the module:
   ```bash
   php bin/magento module:enable FusionLab_Core
   ```
3. Run setup upgrade to register the module:
   ```bash
   php bin/magento setup:upgrade
   ```
4. Flush the cache:
   ```bash
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

## License
See the [LICENSE](./LICENSE) file for more information.

## Support
For questions, issues, or feature requests, please visit our website or GitHub repository:
- **Website**: [https://fusionlab.gr](https://fusionlab.gr)
- **GitHub**: [https://github.com/fusionlab/core](https://github.com/fusionlab/core)

Thank you for using **FusionLab Extensions**!
