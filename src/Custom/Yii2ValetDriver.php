<?php

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
 * This is modified from https://github.com/chinaphp/yii2-valet-driver.
 *
 * ```
 * https://yii2-app-basic.test/index.php?r=site%2Flogin
 *
 * $sitePath /Users/yaozm/Documents/wwwroot/yii2-app-basic
 * $siteName yii2-app-basic
 * $uri /index.php
 * ```
 */
class Yii2ValetDriver extends BasicValetDriver
{
    public function serves(string $sitePath, string $siteName, string $uri): bool
    {
        return (file_exists("{$sitePath}/../yii") && file_exists("{$sitePath}/../vendor/yiisoft/yii2/Yii.php"))
               || (file_exists("{$sitePath}/yii") && file_exists("{$sitePath}/vendor/yiisoft/yii2/Yii.php"));
    }

    public function isStaticFile(string $sitePath, string $siteName, string $uri)
    {
        // this works for domains called code assets
        if (0 === strncmp($siteName, 'assets', 6)) {
            return $sitePath.$uri;
        }

        if ($this->isActualFile($staticFilePath = "{$sitePath}/web{$uri}")) {
            return $staticFilePath;
        }

        return false;
    }

    public function frontControllerPath(string $sitePath, string $siteName, string $uri): ?string
    {
        if (!file_exists("{$sitePath}/web")) {
            exit(
                <<<HTML
                    <pre>
                        <p style='font-size: 18px;'>
                            This may be the advanced version.\n
                            Please link the sub-application directory to Valet(e.g. `cd frontend/ && valet link frontend-yii2`).\n
                            Then visit the linked URL(<a href='http://frontend-yii2.test' target='_blank'>http://frontend-yii2.test</a>).
                        <p>
                    </pre>
                    HTML
            );
        }

        $_SERVER['DOCUMENT_ROOT'] = $sitePath;
        // $_SERVER['SERVER_ADDR'] = '127.0.0.1';
        $_SERVER['SERVER_NAME'] = $_SERVER['HTTP_HOST'];
        $_SERVER['SCRIPT_NAME'] = $_SERVER['PHP_SELF'] = '/index.php';
        $_SERVER['SCRIPT_FILENAME'] = "{$sitePath}/web/index.php";

        return $_SERVER['SCRIPT_FILENAME'];
    }
}
