<?php

declare(strict_types=1);

/**
 * This file is part of the guanguans/valet-drivers.
 *
 * (c) guanguans <ityaozm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$header = <<<'HEADER'
    This file is part of the guanguans/valet-drivers.

    (c) guanguans <ityaozm@gmail.com>

    This source file is subject to the MIT license that is bundled.
    HEADER;

$finder = Finder::create()
    ->in([
        __DIR__.'/Drivers',
    ])
    ->append(glob(__DIR__.'/{*,.*}.php', GLOB_BRACE))
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true)
;

return (new Config())
    ->setFinder($finder)
    ->setRiskyAllowed(false)
    ->setUsingCache(false)
    ->setCacheFile(__DIR__.'/.php-cs-fixer.cache')
    ->setRules([
        '@PHP70Migration' => true,
        // '@PHP70Migration:risky' => true,
        '@PHP71Migration' => true,
        // '@PHP71Migration:risky' => true,
        // '@PHP73Migration' => true,
        // '@PHP74Migration' => true,
        // '@PHP74Migration:risky' => true,
        // '@PHP80Migration' => true,
        // '@PHP80Migration:risky' => true,
        // '@PHP81Migration' => true,
        // '@PHP82Migration' => true,

        '@PhpCsFixer' => true,
        // '@PhpCsFixer:risky' => true,

        // comment
        'header_comment' => [
            'header' => $header,
            'comment_type' => 'PHPDoc',
            'location' => 'after_declare_strict',
            'separate' => 'both',
        ],

        'empty_loop_condition' => false,
    ])
;
