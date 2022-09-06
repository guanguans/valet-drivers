<?php

/**
 * This file is part of the guanguans/valet-drivers.
 *
 * (c) guanguans <ityaozm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Guanguans\ValetDrivers;

use Composer\Script\Event;
use Valet\Filesystem;

class ComposerScripts
{
    public static function installDriver(Event $event)
    {
        require_once $event->getComposer()->getConfig()->get('vendor-dir') . '/autoload.php';

        $io = $event->getIO();
        for (; ;) {
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

        if (! ($filesystem = new Filesystem())->isDir($driversDirectory = VALET_HOME_PATH . '/Drivers')) {
            $io->write("<fg=yellow>Creating the directory($driversDirectory) for driver...</>");
            $filesystem->mkdirAsUser($driversDirectory);
        }

        $files = glob(__DIR__ . '/drivers/*ValetDriver.php');
        foreach ($files as $file) {
            $filesystem->copyAsUser($file, $driversDirectory . '/' . pathinfo($file, PATHINFO_BASENAME));
            $io->write('<info>The `' . pathinfo($file, PATHINFO_FILENAME) . "` have been installed</info>");
        }
    }
}
