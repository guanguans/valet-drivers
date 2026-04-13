<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

/**
 * Copyright (c) 2022-2026 guanguans<ityaozm@gmail.com>
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
     * @see \DG\BypassFinals::enable()
     * @see \get_declared_classes()
     * @see \get_declared_interfaces()
     * @see \get_declared_traits()
     *
     * @return list<class-string>
     *
     * @noinspection PhpUndefinedNamespaceInspection
     */
    function classes(): array
    {
        static $classes;

        if (\is_array($classes)) {
            return $classes;
        }

        /** @var list<list<class-string>> $autoloadClasses */
        $autoloadClasses = array_reduce(
            spl_autoload_functions(),
            static function (array $autoloadClasses, mixed $loader): array {
                if (\is_array($loader) && $loader[0] instanceof ClassLoader) {
                    $autoloadClasses[] = array_keys($loader[0]->getClassMap());
                }

                return $autoloadClasses;
            },
            []
        );

        return $classes = array_values(array_unique(array_merge(
            get_declared_classes(),
            get_declared_interfaces(),
            get_declared_traits(),
            ...$autoloadClasses
        )));
    }
}
