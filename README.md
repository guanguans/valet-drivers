# valet-drivers

[简体中文](README-zh_CN.md) | [ENGLISH](README.md)

> List of drivers for laravel-valet. - laravel-valet 的驱动列表。

[![tests](https://github.com/guanguans/valet-drivers/workflows/tests/badge.svg)](https://github.com/guanguans/valet-drivers/actions)
[![check & fix styling](https://github.com/guanguans/valet-drivers/actions/workflows/php-cs-fixer.yml/badge.svg)](https://github.com/guanguans/valet-drivers/actions)
[![codecov](https://codecov.io/gh/guanguans/valet-drivers/branch/main/graph/badge.svg?token=URGFAWS6S4)](https://codecov.io/gh/guanguans/valet-drivers)
[![Latest Stable Version](https://poser.pugx.org/guanguans/valet-drivers/v)](https://packagist.org/packages/guanguans/valet-drivers)
![GitHub release (latest by date)](https://img.shields.io/github/v/release/guanguans/valet-drivers)
[![Total Downloads](https://poser.pugx.org/guanguans/valet-drivers/downloads)](https://packagist.org/packages/guanguans/valet-drivers)
[![License](https://poser.pugx.org/guanguans/valet-drivers/license)](https://packagist.org/packages/guanguans/valet-drivers)

## Requirement

* PHP >= 8.0

## Installation

Copy the driver `src/Custom/*Driver.php` file to the valet config driver folder `~/.config/valet/Drivers`.

```shell
curl -o ~/.config/valet/Drivers/Yii2ValetDriver.php https://raw.githubusercontent.com/guanguans/valet-drivers/main/src/Custom/Yii2ValetDriver.php
wget -O ~/.config/valet/Drivers/Yii2ValetDriver.php https://raw.githubusercontent.com/guanguans/valet-drivers/main/src/Custom/Yii2ValetDriver.php
```

## Testing

```bash
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
