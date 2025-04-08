<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

/**
 * Copyright (c) 2022-2025 guanguans<ityaozm@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/guanguans/valet-drivers
 */

use Ergebnis\Rector\Rules\Arrays\SortAssociativeArrayByKeyRector;
use Guanguans\ValetDrivers\Support\Rectors\AddNoinspectionsDocCommentToDeclareRector;
use Guanguans\ValetDrivers\Support\Rectors\NewToNewAnonymousImplementsRector;
use Illuminate\Support\Str;
use Rector\CodeQuality\Rector\If_\ExplicitBoolCompareRector;
use Rector\CodeQuality\Rector\LogicalAnd\LogicalToBooleanRector;
use Rector\CodingStyle\Rector\ArrowFunction\StaticArrowFunctionRector;
use Rector\CodingStyle\Rector\Closure\StaticClosureRector;
use Rector\CodingStyle\Rector\Encapsed\EncapsedStringsToSprintfRector;
use Rector\CodingStyle\Rector\Encapsed\WrapEncapsedVariableInCurlyBracesRector;
use Rector\CodingStyle\Rector\FuncCall\ArraySpreadInsteadOfArrayMergeRector;
use Rector\CodingStyle\Rector\Stmt\NewlineAfterStatementRector;
use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassLike\RemoveAnnotationRector;
use Rector\DowngradePhp81\Rector\Array_\DowngradeArraySpreadStringKeyRector;
use Rector\EarlyReturn\Rector\Return_\ReturnBinaryOrToEarlyReturnRector;
use Rector\Php73\Rector\FuncCall\JsonThrowOnErrorRector;
use Rector\PHPUnit\CodeQuality\Rector\Class_\AddSeeTestAnnotationRector;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Renaming\Rector\FuncCall\RenameFunctionRector;
use Rector\Strict\Rector\Empty_\DisallowedEmptyRuleFixerRector;
use Rector\Transform\Rector\FuncCall\FuncCallToStaticCallRector;
use Rector\Transform\ValueObject\FuncCallToStaticCall;
use Rector\ValueObject\PhpVersion;
use Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface;

return RectorConfig::configure()
    ->withPaths([
        __DIR__.'/src/',
        __DIR__.'/tests/',
        ...glob(__DIR__.'/{*,.*}.php', \GLOB_BRACE),
        __DIR__.'/composer-updater',
    ])
    ->withRootFiles()
    // ->withSkipPath(__DIR__.'/tests.php')
    ->withSkip([
        '**/__snapshots__/*',
        '**/Fixtures/*',
        // __FILE__,
    ])
    ->withAutoloadPaths([
        (new ReflectionClass(ReleaseWorkerInterface::class))->getFileName(),
    ])
    ->withBootstrapFiles([
        // __DIR__.'/vendor/symplify/monorepo-builder/vendor/autoload.php',
        // __DIR__.'/vendor/symplify/monorepo-builder/vendor/scoper-autoload.php',
    ])
    ->withCache(__DIR__.'/.build/rector/')
    ->withParallel()
    // ->withoutParallel()
    // ->withImportNames(importNames: false)
    ->withImportNames(importDocBlockNames: false, importShortClasses: false)
    ->withFluentCallNewLine()
    ->withAttributesSets(phpunit: true, all: true)
    ->withComposerBased(phpunit: true)
    ->withPhpVersion(PhpVersion::PHP_80)
    ->withDowngradeSets(php80: true)
    ->withPhpSets(php80: true)
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        codingStyle: true,
        typeDeclarations: true,
        privatization: true,
        naming: true,
        instanceOf: true,
        earlyReturn: true,
        // carbon: true,
        rectorPreset: true,
        phpunitCodeQuality: true,
    )
    ->withSets([
        PHPUnitSetList::PHPUNIT_90,
    ])
    ->withRules([
        AddSeeTestAnnotationRector::class,
        ArraySpreadInsteadOfArrayMergeRector::class,
        JsonThrowOnErrorRector::class,
        SortAssociativeArrayByKeyRector::class,
        StaticArrowFunctionRector::class,
        StaticClosureRector::class,
    ])
    ->withConfiguredRule(AddNoinspectionsDocCommentToDeclareRector::class, [
        'AnonymousFunctionStaticInspection',
        'NullPointerExceptionInspection',
        'PhpPossiblePolymorphicInvocationInspection',
        'PhpUndefinedClassInspection',
        'PhpUnhandledExceptionInspection',
        'StaticClosureCanBeUsedInspection',
    ])
    ->withConfiguredRule(NewToNewAnonymousImplementsRector::class, [
        'Guanguans\ValetDrivers\Contracts\ThrowableContract',
    ])
    ->withConfiguredRule(RemoveAnnotationRector::class, [
        'codeCoverageIgnore',
        'phpstan-ignore',
        'phpstan-ignore-next-line',
        'psalm-suppress',
    ])
    ->withConfiguredRule(FuncCallToStaticCallRector::class, [
        new FuncCallToStaticCall('str', Str::class, 'of'),
    ])
    ->withConfiguredRule(
        RenameFunctionRector::class,
        [
            'Pest\Faker\fake' => 'fake',
            'Pest\Faker\faker' => 'faker',
            'faker' => 'fake',
            'test' => 'it',
        ] + array_reduce(
            [
                'classes',
            ],
            static function (array $carry, string $func): array {
                /** @see https://github.com/laravel/framework/blob/11.x/src/Illuminate/Support/functions.php */
                $carry[$func] = "Guanguans\\ValetDrivers\\Support\\$func";

                return $carry;
            },
            []
        )
    )
    ->withSkip([
        DisallowedEmptyRuleFixerRector::class,
        DowngradeArraySpreadStringKeyRector::class,
        EncapsedStringsToSprintfRector::class,
        ExplicitBoolCompareRector::class,
        LogicalToBooleanRector::class,
        NewlineAfterStatementRector::class,
        ReturnBinaryOrToEarlyReturnRector::class,
        WrapEncapsedVariableInCurlyBracesRector::class,
    ])
    ->withSkip([
        StaticArrowFunctionRector::class => $staticClosureSkipPaths = [
            __DIR__.'/tests',
        ],
        StaticClosureRector::class => $staticClosureSkipPaths,
        SortAssociativeArrayByKeyRector::class => [
            __DIR__.'/src',
            __DIR__.'/tests',
        ],
        AddNoinspectionsDocCommentToDeclareRector::class => [
            __DIR__.'/src/',
            ...glob(__DIR__.'/{*,.*}.php', \GLOB_BRACE),
        ],
    ]);
