# valet-drivers

[简体中文](README-zh_CN.md) | [ENGLISH](README.md)

> List of drivers for laravel-valet. - laravel-valet 的驱动列表。

[![tests](https://github.com/guanguans/valet-drivers/actions/workflows/tests.yml/badge.svg)](https://github.com/guanguans/valet-drivers/actions/workflows/tests.yml)
[![php-cs-fixer](https://github.com/guanguans/valet-drivers/actions/workflows/php-cs-fixer.yml/badge.svg)](https://github.com/guanguans/valet-drivers/actions/workflows/php-cs-fixer.yml)
[![codecov](https://codecov.io/gh/guanguans/valet-drivers/graph/badge.svg?token=0RtgSGom4K)](https://codecov.io/gh/guanguans/valet-drivers)
[![Latest Stable Version](https://poser.pugx.org/guanguans/valet-drivers/v)](https://packagist.org/packages/guanguans/valet-drivers)
[![GitHub release (with filter)](https://img.shields.io/github/v/release/guanguans/valet-drivers)](https://github.com/guanguans/valet-drivers/releases)
[![Total Downloads](https://poser.pugx.org/guanguans/valet-drivers/downloads)](https://packagist.org/packages/guanguans/valet-drivers)
[![License](https://poser.pugx.org/guanguans/valet-drivers/license)](https://packagist.org/packages/guanguans/valet-drivers)

## Requirement

* PHP >= 8.0

## Installation

Copy the driver `src/Drivers/*Driver.php` file to the valet config driver folder `~/.config/valet/Drivers`.

```shell
curl -o ~/.config/valet/Drivers/Yii2ValetDriver.php https://raw.githubusercontent.com/guanguans/valet-drivers/main/src/Drivers/Yii2ValetDriver.php
wget -O ~/.config/valet/Drivers/Yii2ValetDriver.php https://raw.githubusercontent.com/guanguans/valet-drivers/main/src/Drivers/Yii2ValetDriver.php
```

## Composer scripts

```shell
composer app:install-driver
composer checks:required
composer php-cs-fixer:fix
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

* [guanguans](https://github.com/guanguans)
* [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
