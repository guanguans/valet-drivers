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
        for (;;) {
            $isInstalled = strtolower($io->ask("<info>Do you want to install the valet driver?</info> [yes|no]:\n> "));
            if ('no' === $isInstalled || 'n' === $isInstalled) {
                return;
            }

            if ('yes' === $isInstalled || 'y' === $isInstalled) {
                break;
            }

            $io->error('Please answer yes, y, no, or n.');
        }

        if (! ($filesystem = new Filesystem())->isDir($driversDirectory = VALET_HOME_PATH . '/Drivers')) {
            $io->write("<info>Creating directory($driversDirectory) for driver...</info>");
            $filesystem->mkdirAsUser($driversDirectory);
        }

        switch ($event->getOriginatingEvent()->getName()) {
            case 'post-install-cmd':
                $action = 'installed';
                break;
            case 'post-update-cmd':
            default:
                $action = 'updated';
                break;
        }

        $files = glob(__DIR__ . '/drivers/*ValetDriver.php');
        foreach ($files as $file) {
            $filesystem->copyAsUser($file, $driversDirectory . '/' . pathinfo($file, PATHINFO_BASENAME));
            $io->write('<info>The `' . pathinfo($file, PATHINFO_FILENAME) . "` have been $action</info>");
        }
    }
}
