<?php

/** @noinspection GlobalVariableUsageInspection */
/** @noinspection MissingParentCallInspection */
/** @noinspection PhpIllegalPsrClassPathInspection */
declare(strict_types=1);

/**
 * Copyright (c) 2022-2025 guanguans<ityaozm@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/guanguans/valet-drivers
 */

namespace Valet\Drivers\Custom;

use Valet\Drivers\BasicValetDriver;

/**
 * This is modified from https://github.com/myxiaoao/ThinkPHP5ValetDriver.
 */
final class ThinkPHPValetDriver extends BasicValetDriver
{
    public function serves(string $sitePath, string $siteName, string $uri): bool
    {
        return file_exists("$sitePath/think") && file_exists("$sitePath/public/index.php");
    }

    /**
     * @noinspection PhpMissingReturnTypeInspection
     * @noinspection MissingReturnTypeInspection
     */
    public function isStaticFile(string $sitePath, string $siteName, string $uri)
    {
        if ($this->isActualFile($staticFilePath = "$sitePath/public$uri")) {
            return $staticFilePath;
        }

        return false;
    }

    public function frontControllerPath(string $sitePath, string $siteName, string $uri): string
    {
        $_GET['s'] = $uri;
        $_SERVER['DOCUMENT_ROOT'] = $sitePath;
        $_SERVER['SERVER_NAME'] = $_SERVER['HTTP_HOST'];
        $_SERVER['SCRIPT_NAME'] = $_SERVER['PHP_SELF'] = '/index.php';
        $_SERVER['SCRIPT_FILENAME'] = "$sitePath/public/index.php";

        return $_SERVER['SCRIPT_FILENAME'];
    }
}
