<?php

/**
 * This file is part of the guanguans/valet-drivers.
 *
 * (c) guanguans <ityaozm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

/**
 * This is modified from https://github.com/myxiaoao/ThinkPHP5ValetDriver.
 */
class ThinkPHPValetDriver extends BasicValetDriver
{
    /**
     * Determine if the driver serves the request.
     *
     * @param string $sitePath
     * @param string $siteName
     * @param string $uri
     *
     * @return bool
     */
    public function serves($sitePath, $siteName, $uri)
    {
        return file_exists($sitePath.'/think');
    }

    /**
     * Determine if the incoming request is for a static file.
     *
     * @param string $sitePath
     * @param string $siteName
     * @param string $uri
     *
     * @return string|false
     */
    public function isStaticFile($sitePath, $siteName, $uri)
    {
        if ($this->isActualFile($staticFilePath = "$sitePath/public$uri")) {
            return $staticFilePath;
        }

        return false;
    }

    /**
     * Get the fully resolved path to the application's front controller.
     *
     * @param string $sitePath
     * @param string $siteName
     * @param string $uri
     *
     * @return string
     */
    public function frontControllerPath($sitePath, $siteName, $uri)
    {
        $_GET['s'] = $uri;
        $_SERVER['SCRIPT_FILENAME'] = "$sitePath/public/index.php";
        $_SERVER['SCRIPT_NAME'] = $_SERVER['PHP_SELF'].'/index.php';
        $_SERVER['SERVER_NAME'] = $_SERVER['HTTP_HOST'];

        return $_SERVER['SCRIPT_FILENAME'];
    }
}
