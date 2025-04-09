<?php

/** @noinspection AnonymousFunctionStaticInspection */
/** @noinspection NullPointerExceptionInspection */
/** @noinspection PhpPossiblePolymorphicInvocationInspection */
/** @noinspection PhpUndefinedClassInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection StaticClosureCanBeUsedInspection */
declare(strict_types=1);

/**
 * Copyright (c) 2022-2025 guanguans<ityaozm@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/guanguans/valet-drivers
 */

namespace Guanguans\ValetDriversTests\Drivers;

use Valet\Drivers\Custom\CodeIgniter4ValetDriver;

it('can get classes', function (): void {
    expect(new CodeIgniter4ValetDriver)->toBeObject();
})->group(__DIR__, __FILE__);
