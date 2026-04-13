<?php

/** @noinspection DuplicatedCode */
/** @noinspection EfferentObjectCouplingInspection */
/** @noinspection PhpUnused */
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

use Composer\Script\Event;
use Rector\Config\RectorConfig;
use Rector\DependencyInjection\LazyContainerFactory;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Valet\Filesystem;

/**
 * @see https://github.com/laravel/framework/blob/12.x/src/Illuminate/Foundation/ComposerScripts.php
 *
 * @api
 *
 * @internal
 *
 * @property \Symfony\Component\Console\Output\ConsoleOutput $output
 *
 * @method void configureIO(InputInterface $input, OutputInterface $output)
 */
final class ComposerScripts
{
    /**
     * @see \PhpCsFixer\Hasher
     * @see \PhpCsFixer\Utils
     */
    private function __construct() {}

    public static function installDriver(Event $event): int
    {
        self::requireAutoload($event);

        for (; ;) {
            $wantToInstall = strtolower(
                (string) $event->getIO()->ask(
                    "<info>Do you want to install the valet driver?</info> [yes<fg=yellow>(default)</>|no]:\n> ",
                    'yes'
                )
            );

            if ('no' === $wantToInstall || 'n' === $wantToInstall) {
                return 0;
            }

            if ('yes' === $wantToInstall || 'y' === $wantToInstall) {
                break;
            }

            $event->getIO()->error('Please answer yes, y, no, or n.');
        }

        if (!($filesystem = new Filesystem)->isDir($driversDirectory = VALET_HOME_PATH.'/Drivers')) {
            $event->getIO()->warning("Creating the directory($driversDirectory) for driver...");
            $filesystem->mkdirAsUser($driversDirectory);
        }

        $files = glob(__DIR__.'/../Drivers/*ValetDriver.php');

        if (empty($files)) {
            $event->getIO()->error('No valet driver found.');

            exit(1);
        }

        foreach ($files as $file) {
            $filesystem->copyAsUser($file, $driversDirectory.'/'.pathinfo($file, \PATHINFO_BASENAME));
            $event->getIO()->info('The `'.pathinfo($file, \PATHINFO_FILENAME).'` have been installed');
        }

        $event->getIO()->info('No errors');

        return 0;
    }

    public static function makeRectorConfig(): RectorConfig
    {
        static $rectorConfig;

        return $rectorConfig ??= (new LazyContainerFactory)->create();
    }

    /**
     * @noinspection PhpPossiblePolymorphicInvocationInspection
     */
    public static function requireAutoload(Event $event, ?bool $enableDebugging = null): void
    {
        $enableDebugging ??= (new ArgvInput)->hasParameterOption('-vvv', true);
        $enableDebugging and $event->getIO()->enableDebugging(microtime(true));
        (fn () => $this->output->setVerbosity(OutputInterface::VERBOSITY_DEBUG))->call($event->getIO());

        require_once $event->getComposer()->getConfig()->get('vendor-dir').\DIRECTORY_SEPARATOR.'autoload.php';
    }

    /**
     * @param null|list<string> $argv
     */
    public static function makeArgvInput(?array $argv = null, ?InputDefinition $inputDefinition = null): ArgvInput
    {
        static $argvInput;

        return $argvInput ??= new ArgvInput($argv, $inputDefinition);
    }

    /**
     * @see \Rector\Console\Style\SymfonyStyleFactory
     */
    public static function makeSymfonyStyle(?InputInterface $input = null, ?OutputInterface $output = null): SymfonyStyle
    {
        static $symfonyStyle;

        if (
            $symfonyStyle instanceof SymfonyStyle
            && (
                !$input instanceof InputInterface
                || (string) \Closure::bind(
                    static fn (SymfonyStyle $symfonyStyle): InputInterface => $symfonyStyle->input,
                    null,
                    SymfonyStyle::class
                )($symfonyStyle) === (string) $input
            )
            && (
                !$output instanceof OutputInterface
                || \Closure::bind(
                    static fn (SymfonyStyle $symfonyStyle): OutputInterface => $symfonyStyle->output,
                    null,
                    SymfonyStyle::class
                )($symfonyStyle) === $output
            )
        ) {
            return $symfonyStyle;
        }

        $input ??= new ArgvInput;
        $output ??= new ConsoleOutput;

        // to configure all -v, -vv, -vvv options without memory-lock to Application run() arguments
        (fn () => $this->configureIO($input, $output))->call(new Application);

        // --debug or --xdebug is called
        if ($input->hasParameterOption(['--debug', '--xdebug'], true)) {
            $output->setVerbosity(OutputInterface::VERBOSITY_DEBUG);
        }

        // disable output for testing
        if (self::isRunningInTesting()) {
            $output->setVerbosity(OutputInterface::VERBOSITY_QUIET);
        }

        return $symfonyStyle = new SymfonyStyle($input, $output);
    }

    public static function isRunningInTesting(): bool
    {
        return 'testing' === getenv('ENV');
    }
}
