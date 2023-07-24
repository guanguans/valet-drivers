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
 * This is modified from https://github.com/DCzajkowski/DistValetDriver.
 */
class DistValetDriver extends BasicValetDriver
{
    public function serves(string $sitePath, string $siteName, string $uri): bool
    {
        return file_exists($sitePath.'/dist/index.html');
    }

    public function isStaticFile(string $sitePath, string $siteName, string $uri)
    {
        if ($this->isActualFile($staticFilePath = $sitePath.'/dist'.$uri)) {
            return $staticFilePath;
        }

        return false;
    }

    public function frontControllerPath(string $sitePath, string $siteName, string $uri): ?string
    {
        return $sitePath.'/dist/index.html';
    }
}
