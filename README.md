<div id="top"></div>

<!-- PROJECT SHIELDS -->

[![Contributors][contributors-shield]][contributors-url]
[![Forks][forks-shield]][forks-url]
[![Stargazers][stars-shield]][stars-url]
[![Issues][issues-shield]][issues-url]
[![Packagist][packagist-shield]][packagist-url]
[![PHP Version][php-shield]][php-url]
[![Apache 2.0 License][license-shield]][license-url]

<br />
<div align="center">
  <a href="https://github.com/smartpay-co/smartpay-magento2">
		<picture>
			<source media="(prefers-color-scheme: dark)" srcset="https://assets.smartpay.co/logo/banner/smartpay-logo-dark.png" />
			<source media="(prefers-color-scheme: light)" srcset="https://assets.smartpay.co/logo/banner/smartpay-logo.png" />
			<img alt="Smartpay" src="https://assets.smartpay.co/logo/banner/smartpay-logo.png" style="width: 797px;" />
		</picture>
  </a>

  <p align="center">
    <a href="https://en.docs.smartpay.co/docs/magento"><strong>Explore the docs »</strong></a>
    <br />
    <br />
    <a href="https://github.com/smartpay-co/smartpay-magento2/issues">Report Bug</a>
    ·
    <a href="https://github.com/smartpay-co/smartpay-magento2/issues">Request Feature</a>
  </p>
</div>

# Smartpay Payment plugin for Magento2
Use Smartpay's plugin for Magento 2 to offer frictionless payments in your store.

## Requirements
This plugin supports Magento2 version
* 2.3 and higher (PHP 8 is not supported currently)
* 2.4 and higher

## Features
* Dead-simple plugin installation
* Smartpay checkout experience
* Deep integration with Magento order workflow
* Automatic & manual capture
* [On-site messaging](https://en.docs.smartpay.co/docs/on-site-messaging) to boost sales


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

Distributed under the MIT License. See `LICENSE.txt` for more information.

<!-- MARKDOWN LINKS & IMAGES -->
<!-- https://www.markdownguide.org/basic-syntax/#reference-style-links -->
[contributors-shield]: https://img.shields.io/github/contributors/smartpay-co/smartpay-magento2.svg
[contributors-url]: https://github.com/smartpay-co/smartpay-magento2/graphs/contributors
[forks-shield]: https://img.shields.io/github/forks/smartpay-co/smartpay-magento2.svg
[forks-url]: https://github.com/smartpay-co/smartpay-magento2/network/members
[stars-shield]: https://img.shields.io/github/stars/smartpay-co/smartpay-magento2.svg
[stars-url]: https://github.com/smartpay-co/smartpay-magento2/stargazers
[issues-shield]: https://img.shields.io/github/issues/smartpay-co/smartpay-magento2.svg
[issues-url]: https://github.com/smartpay-co/smartpay-magento2/issues
[license-shield]: https://img.shields.io/github/license/smartpay-co/smartpay-magento2.svg
[license-url]: https://github.com/smartpay-co/smartpay-magento2/blob/main/LICENSE.txt
[packagist-shield]: https://img.shields.io/packagist/v/smartpay-co/smartpay-magento2.svg
[packagist-url]: https://packagist.org/packages/smartpay-co/smartpay-magento2
[php-shield]: https://img.shields.io/packagist/php-v/smartpay-co/smartpay-magento2.svg?logo=php&logoColor=white
[php-url]: https://packagist.org/packages/smartpay-co/smartpay-magento2
