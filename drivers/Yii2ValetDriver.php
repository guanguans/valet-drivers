<?php

/**
 * This file is part of the guanguans/valet-drivers.
 *
 * (c) guanguans <ityaozm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

/**
 * This is modified from https://github.com/chinaphp/yii2-valet-driver.
 */
class Yii2ValetDriver extends ValetDriver
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
        return (file_exists($sitePath.'/../vendor/yiisoft/yii2/Yii.php') || file_exists($sitePath.'/vendor/yiisoft/yii2/Yii.php'))
               && file_exists($sitePath.'/yii');
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
        // this works for domains called code assets
        // for example your site name product.{valet-domen} assets domen is assets.product.{valet-domen}
        if (0 === strpos($siteName, 'assets')) {
            return $sitePath.$uri;
        }

        if (
            file_exists($staticFilePath = $sitePath.'/web/'.$uri)
            && ! is_dir($staticFilePath)
            && '.php' !== pathinfo($staticFilePath)['extension']) {
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
        $uriPath = explode('/', $uri)[1];

        if (file_exists($sitePath.'/web/'.$uriPath.'/index.php') && ! empty($uriPath)) {
            $_SERVER['DOCUMENT_ROOT'] = $sitePath;
            $_SERVER['PHP_SELF'] = '/'.$uriPath.'/index.php';
            $_SERVER['SCRIPT_FILENAME'] = $sitePath.'/web/'.$uriPath.'/index.php';
            $_SERVER['SCRIPT_NAME'] = '/'.$uriPath.'/index.php';

            return $sitePath.'/web/'.$uriPath.'/index.php';
        }

        $_SERVER['DOCUMENT_ROOT'] = $sitePath;
        $_SERVER['PHP_SELF'] = '/index.php';
        $_SERVER['SERVER_NAME'] = $_SERVER['HTTP_HOST'];
        $_SERVER['SCRIPT_FILENAME'] = $sitePath.'/web/index.php';
        $_SERVER['SCRIPT_NAME'] = '/index.php';

        return $sitePath.'/web/index.php';
    }
}
