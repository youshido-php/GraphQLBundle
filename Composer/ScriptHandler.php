<?php
/*
* This file is a part of graphql-bundle project.
*
* @author Alexandr Viniychuk <a@viniychuk.com>
* created: 9/3/16 8:24 AM
*/

namespace Youshido\GraphQLBundle\Composer;

use Composer\Script\Event;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;


class ScriptHandler
{

    public static function configure(Event $event)
    {
        if (!$event->getIO()->askConfirmation('Create a boilerplate Schema? [Y/n] ', true)) {
            return;
        }
        $vendorDir = $event->getComposer()->getConfig()->get('vendor-dir');
        $rootDir   = realpath($vendorDir . '/..');
        static::executeCommand($event, static::getConsoleDir($rootDir), 'graphql:configure');
    }

    protected static function getPhp()
    {
        $phpFinder = new PhpExecutableFinder();
        if (!$phpPath = $phpFinder->find()) {
            throw new \RuntimeException('The php executable could not be found, add it to your PATH environment variable and try again');
        }

        return $phpPath;
    }

    protected static function executeCommand(Event $event, $consoleDir, $cmd, $timeout = 300)
    {
        $php     = escapeshellarg(static::getPhp());
        $console = escapeshellarg($consoleDir . '/console');
        if ($event->getIO()->isDecorated()) {
            $console .= ' --ansi';
        }

        $process = new Process($php . ' ' . $console . ' ' . $cmd, null, null, null, $timeout);
        $process->run(function ($type, $buffer) use ($event) { $event->getIO()->write($buffer, false); });
        if (!$process->isSuccessful()) {
            throw new \RuntimeException(sprintf("An error occurred when executing the \"%s\" command:\n\n%s\n\n%s.", escapeshellarg($cmd), $process->getOutput(), $process->getErrorOutput()));
        }
    }

    protected static function getConsoleDir($rootDir)
    {
        if (is_file($rootDir . 'app/console')) {
            return $rootDir . 'app/';
        } elseif (is_file($rootDir . 'bin/console')) {
            return $rootDir . 'bin/';
        } else {
            throw new \Exception('Cannot find Symfony console command');
        }
    }
}