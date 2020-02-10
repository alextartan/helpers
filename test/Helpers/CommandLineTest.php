<?php

namespace AlexTartanTest\Helpers;

use AlexTartan\Helpers\CommandLine;
use PHPUnit\Framework\TestCase;

class CommandLineTest extends TestCase
{
    public function testSimpleCommand(): void
    {
        $commandLine = new CommandLine(
            'ls',
            null,
            null,
            null
        );

        self::assertSame(
            'ls',
            $commandLine->getFullCommand()
        );
    }

    public function testSimpleCommandWithWorkingDir(): void
    {
        $commandLine = new CommandLine(
            'ls',
            '/opt',
            null,
            null
        );

        self::assertSame(
            "cd '/opt' && ls",
            $commandLine->getFullCommand()
        );
    }

    public function testSimpleCommandWithWorkingDirAndStdErr(): void
    {
        $commandLine = new CommandLine(
            'ls',
            '/opt',
            CommandLine::STDERR_TO_STDOUT,
            null
        );

        self::assertSame(
            "cd '/opt' && ls 2>&1",
            $commandLine->getFullCommand()
        );
    }

    public function testSimpleCommandWithWorkingDirAndStdErrAndStdOut(): void
    {
        $commandLine = new CommandLine(
            'ls',
            '/opt',
            CommandLine::STDERR_TO_STDOUT,
            CommandLine::STDOUT_TO_DEV_NULL
        );

        self::assertSame(
            "cd '/opt' && ls 2>&1 > /dev/null",
            $commandLine->getFullCommand()
        );
    }

    public function testSimpleCommandWithWorkingDirAndStdErrAndStdOutNonDefault(): void
    {
        $commandLine = new CommandLine(
            'ls',
            '/opt',
            '/var/log/err',
            '/var/log/out'
        );

        self::assertSame(
            "cd '/opt' && ls 2> '/var/log/err' > '/var/log/out'",
            $commandLine->getFullCommand()
        );
    }

    public function testAdvancedCommandWithWorkingDirAndStdErrAndStdOut(): void
    {
        $commandLine = (new CommandLine(
            'ls',
            '/opt',
            CommandLine::STDERR_TO_STDOUT,
            CommandLine::STDOUT_TO_DEV_NULL
        ))
            ->withArgument('-la')
            ->withOption('--color', 'auto', '=');

        self::assertSame(
            "cd '/opt' && ls '-la' --color='auto' 2>&1 > /dev/null",
            $commandLine->getFullCommand()
        );
    }

    public function testAdvancedCommandWithWorkingDirAndStdErrAndStdOutAppendAndWrite(): void
    {
        $commandLine = (new CommandLine(
            'ls',
            '/opt',
            CommandLine::STDERR_TO_STDOUT,
            CommandLine::STDOUT_TO_DEV_NULL,
            CommandLine::MODE_APPEND,
            CommandLine::MODE_APPEND
        ))
            ->withArgument('-la')
            ->withOption('--color', 'auto', '=');

        self::assertSame(
            "cd '/opt' && ls '-la' --color='auto' 2>&1 >> /dev/null",
            $commandLine->getFullCommand()
        );
    }

    public function testSimpleCommandWithWorkingDirAndStdErrAndStdOutNonDefaultAppendAndWrite(): void
    {
        $commandLine = new CommandLine(
            'ls',
            '/opt',
            '/var/log/err',
            '/var/log/out',
            CommandLine::MODE_APPEND,
            CommandLine::MODE_WRITE
        );

        self::assertSame(
            "cd '/opt' && ls 2>> '/var/log/err' > '/var/log/out'",
            $commandLine->getFullCommand()
        );
    }

    public function testSimpleCommandWithWorkingDirAndStdErrAndStdOutNonDefaultAppendAndWriteBackgroundTask(): void
    {
        $commandLine = new CommandLine(
            'ls',
            '/opt',
            '/var/log/err',
            '/var/log/out',
            CommandLine::MODE_APPEND,
            CommandLine::MODE_WRITE,
            true
        );

        self::assertSame(
            "cd '/opt' && ls 2>> '/var/log/err' > '/var/log/out' &",
            $commandLine->getFullCommand()
        );
    }
}
