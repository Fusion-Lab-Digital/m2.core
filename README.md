# Magento 2 Module: FusionLab Core

## Introduction
**FusionLab Core**, acts as the cornerstone for FusionLab extensions, offering critical functionalities and tools that enable and support the seamless operation of other FusionLab modules.

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
This module does not require configuration directly. It works as a dependency for other FusionLab modules.

## Tracking Information
This module collects **non-personal data** to improve compatibility and performance. The data collected includes:
- Domain name
- PHP version
- MySQL version
- Usage of FusionLab modules

### Disable Tracking
To disable tracking:
1. Go to **Stores > Configuration > FusionLab Core > Enable Tracking**.
2. Set the **Enable Tracking** option to **No**.

No personal data is collected, and this data is used solely for improving the software.

## License
```plaintext
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
```

See the [LICENSE](./LICENSE) file for more information.

## Support
For questions, issues, or feature requests, please visit our website or GitHub repository:
- **Website**: [https://fusionlab.gr](https://fusionlab.gr)
- **GitHub**: [https://github.com/fusionlab/core](https://github.com/fusionlab/core)

Thank you for using **FusionLab Core**!
