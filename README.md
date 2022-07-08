# Smartpay Payment plugin for Magento2
Use Smartpay's plugin for Magento 2 to offer frictionless payments in your store.

## Requirements
This plugin supports Magento2 version
* 2.3 and higher
* 2.4 and higher

## Installation
You can install our plugin through Composer:
```
composer require smartpay-co/smartpay-magento2
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento cache:clean
bin/magento setup:static-content:deploy
```

## API Library
This module is using the Smartpay APIs Library for PHP for all (API) connections to Smartpay.
<a href="https://github.com/smartpay-co/sdk-php" target="_blank">This library can be found here</a>

## License
MIT license. For more information, see the [LICENSE](LICENSE.md) file.
