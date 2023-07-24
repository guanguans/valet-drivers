<?php

/**
 * This file is part of the guanguans/valet-drivers.
 *
 * (c) guanguans <ityaozm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Valet\Drivers\Custom;

use Valet\Drivers\BasicValetDriver;

/**
 * This is modified from https://github.com/liboysoft/thinkphp3-valet-driver.
 */
class ThinkPHP3ValetDriver extends BasicValetDriver
{
    public function serves(string $sitePath, string $siteName, string $uri): bool
    {
        return file_exists($sitePath.'/ThinkPHP') && file_exists($sitePath.'/index.php');
    }

    public function isStaticFile(string $sitePath, string $siteName, string $uri)
    {
        if ($this->isActualFile($staticFilePath = $sitePath.$uri)) {
            return $staticFilePath;
        }

        return false;
    }

    public function frontControllerPath(string $sitePath, string $siteName, string $uri): ?string
    {
        $_GET['s'] = $uri;
        $_SERVER['DOCUMENT_ROOT'] = $sitePath;
        $_SERVER['SERVER_NAME'] = $_SERVER['HTTP_HOST'];
        $_SERVER['SCRIPT_NAME'] = $_SERVER['PHP_SELF'] = '/index.php';
        $_SERVER['SCRIPT_FILENAME'] = $sitePath.'/index.php';

        return $_SERVER['SCRIPT_FILENAME'];
    }
}
