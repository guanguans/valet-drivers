<?php

declare(strict_types=1);

/**
 * Copyright (c) 2022-2026 guanguans<ityaozm@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/guanguans/valet-drivers
 */

use Ergebnis\PhpCsFixer\Config\Factory;
use Ergebnis\PhpCsFixer\Config\Fixers;
use Ergebnis\PhpCsFixer\Config\Rules;
use Ergebnis\PhpCsFixer\Config\RuleSet\Php80;
use Guanguans\PhpCsFixerCustomFixers\Support\Utils;

require __DIR__.'/vendor/autoload.php';

return Factory::fromRuleSet(Php80::create()
    ->withHeader(Utils::header('guanguans/valet-drivers', '2022', __DIR__.'/LICENSE'))
    ->withCustomFixers(Fixers::fromFixers(... require __DIR__.'/vendor/guanguans/php-cs-fixer-custom-fixers/config/custom-fixers.php'))
    ->withRules(Rules::fromArray(require __DIR__.'/vendor/guanguans/php-cs-fixer-custom-fixers/config/custom-rules.php'))
    ->withRules(Rules::fromArray(require __DIR__.'/vendor/guanguans/php-cs-fixer-custom-fixers/config/rules.php'))
    ->withRules(Rules::fromArray([
        '@autoPHPUnitMigration:risky' => true,
    ])))
    ->setUsingCache(true)
    ->setCacheFile(\sprintf('%s/.build/php-cs-fixer/%s.cache', __DIR__, pathinfo(__FILE__, \PATHINFO_FILENAME)))
    ->setUnsupportedPhpVersionAllowed(true)
    ->setFinder(Utils::defaultFinder());
