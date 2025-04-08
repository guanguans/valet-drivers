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

use ShipMonk\ComposerDependencyAnalyser\Config\Configuration;
use ShipMonk\ComposerDependencyAnalyser\Config\ErrorType;

return (new Configuration)
    ->addPathsToScan(
        [
            __DIR__.'/src/',
        ],
        false
    )
    ->addPathsToExclude([
        __DIR__.'/src/Support/Rectors/',
        __DIR__.'/src/Support/ComposerScripts.php',
        __DIR__.'/tests/',
    ])
    // ->ignoreErrorsOnPackages(
    //     [
    //         'laravel/valet',
    //     ],
    //     [ErrorType::DEV_DEPENDENCY_IN_PROD],
    // )
    ->ignoreUnknownClasses([
        'Valet\Drivers\BasicValetDriver',
    ]);
