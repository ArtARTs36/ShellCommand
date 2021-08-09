<?php

namespace ArtARTs36\ShellCommand\Result;

use ArtARTs36\Str\Str;

final class CommandResult
{
    private $commandLine;

    private $stdout;

    private $date;

    private $stderr;

    private $code;

    public function __construct(
        string $commandLine,
        Str $result,
        \DateTimeInterface $date,
        Str $stderr,
        ?int $code = null
    ) {
        $this->commandLine = $commandLine;
        $this->stdout = $result;
        $this->date = $date;
        $this->stderr = $stderr;
        $this->code = $code;
    }

    public function getCommandLine(): string
    {
        return $this->commandLine;
    }

    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    public function getResult(): Str
    {
        return $this->stdout;
    }

    /**
     * from stderr
     */
    public function getError(): Str
    {
        return $this->stderr;
    }

    public function isNull(): bool
    {
        return $this->stdout === null;
    }

    public function isEmpty(): bool
    {
        return empty($this->stdout) && empty($this->stderr);
    }

    public function getCode(): ?int
    {
        return $this->code;
    }

    public function __toString()
    {
        return $this->stdout->__toString();
    }
}
