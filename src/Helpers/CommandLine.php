<?php

namespace AlexTartan\Helpers;

use function escapeshellarg;
use function escapeshellcmd;

class CommandLine
{
    public const STDERR_TO_STDOUT   = ' 2>&1';
    public const STDOUT_TO_DEV_NULL = '/dev/null';

    private string $command;
    private ?string $workingDirectory;
    private ?string $stdOut = '';
    private ?string $stdErr = '';

    public function __construct(string $command, ?string $workingDirectory, ?string $stdErr, ?string $stdOut)
    {
        $this->command          = escapeshellcmd($command);
        $this->workingDirectory = $workingDirectory;

        if ($stdErr === self::STDERR_TO_STDOUT) {
            $this->stdErr = self::STDERR_TO_STDOUT;
        } elseif ($stdErr !== null) {
            $this->stdErr = ' 2> ' . escapeshellarg($stdErr);
        }

        if ($stdOut === self::STDOUT_TO_DEV_NULL) {
            $this->stdOut = ' > ' . $stdOut;
        } elseif ($stdOut !== null) {
            $this->stdOut = ' 2> ' . ' > ' . escapeshellarg($stdOut);
        }
    }

    public function withOption($option, $value = null, $separator = ' '): self
    {
        $command          = clone $this;
        $command->command .= ' ' . $option;
        if ($value !== null) {
            $command->command .= $separator . escapeshellarg($value);
        }

        return $command;
    }

    public function withArgument($argument): self
    {
        $command          = clone $this;
        $command->command .= ' ' . escapeshellarg($argument);

        return $command;
    }

    public function getFullCommand(): string
    {
        $prefix = $this->workingDirectory === null
            ? ''
            : 'cd ' . escapeshellarg($this->workingDirectory) . ' && ';

        $fullCommand = $prefix . $this->command;
        if ($this->stdErr !== null) {
            $fullCommand .= $this->stdErr;
        }
        if ($this->stdOut !== null) {
            $fullCommand .= $this->stdOut;
        }

        return $fullCommand;
    }

    /** @codeCoverageIgnore */
    public function exec(array &$output = null, int &$returnVar = null): string
    {
        return exec($this->getFullCommand(), $output, $returnVar);
    }

    /** @codeCoverageIgnore */
    public function shell_exec(): ?string
    {
        return shell_exec($this->getFullCommand());
    }

    /** @codeCoverageIgnore */
    public function system(&$returnVar = null): ?string
    {
        $result = system($this->getFullCommand(), $returnVar);

        return $result === false
            ? null
            : $result;
    }

    /** @codeCoverageIgnore */
    public function passthru(int &$returnValue = null): void
    {
        passthru($this->getFullCommand(), $returnValue);
    }
}