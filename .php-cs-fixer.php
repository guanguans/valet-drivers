<?php

/**
 * This file is part of the guanguans/valet-drivers.
 *
 * (c) guanguans <ityaozm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

$header = <<<EOF
This file is part of the guanguans/valet-drivers.

(c) guanguans <ityaozm@gmail.com>

This source file is subject to the MIT license that is bundled.
EOF;

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__.'/drivers',
    ])
    ->append([
        __DIR__.'/.php-cs-fixer.php',
        // __DIR__.'/ComposerScripts.php',
    ])
    ->exclude([
        '.github/',
        'doc/',
        'docs/',
        'vendor/',
    ])
    ->notPath([
        'bootstrap/*',
        'storage/*',
        'resources/view/mail/*',
        'vendor/*',
    ])
    ->name('*.php')
    ->notName([
        '*.blade.php',
        '_ide_helper.php',
    ])
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return (new PhpCsFixer\Config())
    ->setRules([
        // '@DoctrineAnnotation' => true,
        // '@PHP80Migration:risky' => true,
        // '@PHPUnit84Migration:risky' => true,
        // '@PSR12:risky' => true,
        '@Symfony' => true,
        'header_comment' => [
            'header' => $header,
            'comment_type' => 'PHPDoc',
        ],
        'blank_line_before_statement' => [
            'statements' => ['break', 'continue', 'declare', 'return', 'throw', 'try'],
        ],
        // 'comment_to_phpdoc' => [
        //     'ignored_tags' => [],
        // ],
        // 'declare_strict_types' => true,
        'method_argument_space' => [
            'on_multiline' => 'ensure_fully_multiline',
        ],
        'not_operator_with_successor_space' => true,
        'no_useless_return' => true,
        'no_useless_else' => true,
        // 'is_null' => true,
        'return_assignment' => true,
        'multiline_comment_opening_closing' => true,
        'align_multiline_comment' => [
            'comment_type' => 'phpdocs_only',
        ],
        'phpdoc_to_comment' => [],
        'phpdoc_var_annotation_correct_order' => true,
        // 'php_unit_construct' => [
        //     'assertions' => ['assertEquals', 'assertSame', 'assertNotEquals', 'assertNotSame'],
        // ],
        'array_indentation' => true,
        'method_chaining_indentation' => true,
        'statement_indentation' => true,
    ])
    // ->setRiskyAllowed(true)
    ->setFinder($finder);
