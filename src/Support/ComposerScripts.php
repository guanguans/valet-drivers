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

use Composer\Script\Event;
use Rector\Config\RectorConfig;
use Rector\DependencyInjection\LazyContainerFactory;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Style\SymfonyStyle;
use Valet\Filesystem;

final class ComposerScripts
{
    public static function installDriver(Event $event): void
    {
        self::requireAutoload($event);

        $io = $event->getIO();

        while (true) {
            $wantToInstall = strtolower(
                $io->ask(
                    "<info>Do you want to install the valet driver?</info> [yes<fg=yellow>(default)</>|no]:\n> ",
                    'yes'
                )
            );

            if ('no' === $wantToInstall || 'n' === $wantToInstall) {
                return;
            }

            if ('yes' === $wantToInstall || 'y' === $wantToInstall) {
                break;
            }

            $io->error('Please answer yes, y, no, or n.');
        }

        if (!($filesystem = new Filesystem)->isDir($driversDirectory = VALET_HOME_PATH.'/Drivers')) {
            $io->write("<fg=yellow>Creating the directory({$driversDirectory}) for driver...</>");
            $filesystem->mkdirAsUser($driversDirectory);
        }

        $files = glob(__DIR__.'/../Drivers/*ValetDriver.php');

        if (empty($files)) {
            $io->error('No valet driver found.');

            exit(1);
        }

        foreach ($files as $file) {
            $filesystem->copyAsUser($file, $driversDirectory.'/'.pathinfo($file, \PATHINFO_BASENAME));
            $io->write('<info>The `'.pathinfo($file, \PATHINFO_FILENAME).'` have been installed</info>');
        }
    }

    /**
     * @noinspection PhpUnused
     */
    public static function makeRectorConfig(): RectorConfig
    {
        return (new LazyContainerFactory)->create();
    }

    /**
     * @noinspection PhpUnused
     */
    public static function makeSymfonyStyle(): SymfonyStyle
    {
        return new SymfonyStyle(new ArgvInput, new ConsoleOutput);
    }

    public static function requireAutoload(?Event $event = null): void
    {
        if ($event instanceof Event) {
            $vendorDir = $event->getComposer()->getConfig()->get('vendor-dir');

            require_once $vendorDir.'/autoload.php';

            return;
        }

        $possibleAutoloadPaths = [
            __DIR__.'/../../vendor/autoload.php',
            __DIR__.'/../../../../../vendor/autoload.php',
        ];

        foreach ($possibleAutoloadPaths as $possibleAutoloadPath) {
            if (file_exists($possibleAutoloadPath)) {
                require_once $possibleAutoloadPath;

                break;
            }
        }
    }
}
