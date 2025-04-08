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
use Valet\Filesystem;

class ComposerScripts
{
    public static function installDriver(Event $event): void
    {
        require_once $event->getComposer()->getConfig()->get('vendor-dir').'/autoload.php';

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

        $files = glob(__DIR__.'/Drivers/*ValetDriver.php');

        if (!\is_array($files)) {
            $io->error('No valet driver found.');

            exit(1);
        }

        foreach ($files as $file) {
            $filesystem->copyAsUser($file, $driversDirectory.'/'.pathinfo($file, \PATHINFO_BASENAME));
            $io->write('<info>The `'.pathinfo($file, \PATHINFO_FILENAME).'` have been installed</info>');
        }
    }
}
