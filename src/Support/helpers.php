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

namespace Guanguans\ValetDrivers\Support;

use Composer\Autoload\ClassLoader;

if (!\function_exists('Guanguans\ValetDrivers\Support\classes')) {
    /**
     * @see \get_declared_classes()
     * @see \get_declared_interfaces()
     * @see \get_declared_traits()
     * @see \DG\BypassFinals::enable()
     *
     * @return list<class-string>
     */
    function classes(): array
    {
        /** @var list<list<string>> $classes */
        static $classes = [];

        if ($classes) {
            return $classes;
        }

        foreach (spl_autoload_functions() as $loader) {
            if (\is_array($loader) && $loader[0] instanceof ClassLoader) {
                $classes[] = array_keys($loader[0]->getClassMap());
            }
        }

        return array_unique(array_merge(
            get_declared_classes(),
            get_declared_interfaces(),
            get_declared_traits(),
            ...$classes
        ));
    }
}
