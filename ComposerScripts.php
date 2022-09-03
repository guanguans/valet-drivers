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
        require_once $event->getComposer()->getConfig()->get('vendor-dir').'/autoload.php';

        $io = $event->getIO();

        for (;;) {
            $isInstalled = strtolower($io->ask("Whether to install the valet driver? [yes|no]:\n>"));
            if ('no' === $isInstalled) {
                return;
            }

            if ('yes' === $isInstalled) {
                break;
            }
        }

        $io->write('Installing driver for valet...');

        if (! ($filesystem = new Filesystem())->isDir($driversDirectory = VALET_HOME_PATH.'/Drivers')) {
            $filesystem->mkdirAsUser($driversDirectory);
        }

        $files = glob(__DIR__.'/drivers/*.php');
        foreach ($files as $file) {
            $filesystem->copyAsUser($file, $driversDirectory.'/'.pathinfo($file, PATHINFO_BASENAME));
        }

        $io->write('The driver have been installed');
    }
}
