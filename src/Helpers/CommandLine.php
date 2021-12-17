<?php

namespace AlexTartan\Helpers;

use Webmozart\Assert\Assert;

use function escapeshellarg;
use function escapeshellcmd;

class CommandLine
{
    public const STDERR_TO_STDOUT   = ' 2>&1';
    public const STDOUT_TO_DEV_NULL = '/dev/null';

    public const MODE_WRITE  = '>';
    public const MODE_APPEND = '>>';

    private string $command;
    private ?string $workingDirectory;
    private ?string $stdOut = '';
    private ?string $stdErr = '';
    private bool $background;

    public function __construct(
        string $command,
        ?string $workingDirectory,
        ?string $stdErr,
        ?string $stdOut,
        string $stdErrMode = self::MODE_WRITE,
        string $stdOutMode = self::MODE_WRITE,
        bool $background = false
    ) {
        $this->command          = escapeshellcmd($command);
        $this->workingDirectory = $workingDirectory;
        $this->background       = $background;

        Assert::oneOf($stdErrMode, [self::MODE_WRITE, self::MODE_APPEND]);
        Assert::oneOf($stdOutMode, [self::MODE_WRITE, self::MODE_APPEND]);

        if ($stdErr === self::STDERR_TO_STDOUT) {
            $this->stdErr = self::STDERR_TO_STDOUT;
        } elseif ($stdErr !== null) {
            $this->stdErr = ' 2' . $stdErrMode . ' ' . escapeshellarg($stdErr);
        }

        if ($stdOut === self::STDOUT_TO_DEV_NULL) {
            $this->stdOut = ' ' . $stdOutMode . ' ' . $stdOut;
        } elseif ($stdOut !== null) {
            $this->stdOut = ' ' . $stdOutMode . ' ' . escapeshellarg($stdOut);
        }
    }

    public function withOption(string $option, string $value = null, string $separator = ' '): self
    {
        $command          = clone $this;
        $command->command .= ' ' . $option;
        if ($value !== null) {
            $command->command .= $separator . escapeshellarg($value);
        }

        return $command;
    }

    public function withArgument(string $argument): self
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

        if ($this->background) {
            $fullCommand .= ' &';
        }

        return $fullCommand;
    }

    /** @codeCoverageIgnore */
    public function exec(array &$output = null, int &$returnVar = null): string|false
    {
        return exec($this->getFullCommand(), $output, $returnVar);
    }

    /** @codeCoverageIgnore */
    public function shellExec(): string|false|null
    {
        return shell_exec($this->getFullCommand());
    }

    /** @codeCoverageIgnore */
    public function system(int &$returnVar = null): ?string
    {
        /** @var string|false $result */
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
