<?php

namespace ArtARTs36\ShellCommand\Result;

use ArtARTs36\Str\Str;

class CommandResult
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
        int $code
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
        return $this->stdout->isEmpty() && $this->stderr->isEmpty();
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function isOk(): bool
    {
        return $this->code === ResultCode::OK;
    }

    public function isFail(): bool
    {
        return ! $this->isOk();
    }

    public function __toString()
    {
        return $this->stdout->__toString();
    }
}
