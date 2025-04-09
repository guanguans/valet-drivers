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

## 环境要求

* PHP >= 8.0

## 安装

拷贝驱动 `src/Drivers/*Driver.php` 文件到 valet 配置驱动文件夹 `~/.config/valet/Drivers`。

```shell
curl -o ~/.config/valet/Drivers/Yii2ValetDriver.php https://raw.githubusercontent.com/guanguans/valet-drivers/main/src/Drivers/Yii2ValetDriver.php
wget -O ~/.config/valet/Drivers/Yii2ValetDriver.php https://raw.githubusercontent.com/guanguans/valet-drivers/main/src/Drivers/Yii2ValetDriver.php
```

## 测试

```bash
composer test
```

## 变更日志

请参阅 [CHANGELOG](CHANGELOG.md) 获取最近有关更改的更多信息。

## 贡献指南

请参阅 [CONTRIBUTING](.github/CONTRIBUTING.md) 有关详细信息。

## 安全漏洞

请查看[我们的安全政策](../../security/policy)了解如何报告安全漏洞。

## 贡献者

* [guanguans](https://github.com/guanguans)
* [所有贡献者](../../contributors)

## 协议

MIT 许可证（MIT）。有关更多信息，请参见[协议文件](LICENSE)。
