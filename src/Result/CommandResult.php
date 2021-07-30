<?php

namespace ArtARTs36\ShellCommand\Result;

class CommandResult
{
    private $commandLine;

    private $result;

    private $date;

    private $code;

    public function __construct(string $commandLine, ?string $result, \DateTimeInterface $date, int $code)
    {
        $this->commandLine = $commandLine;
        $this->result = $result;
        $this->date = $date;
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

    public function getResult(): ?string
    {
        return $this->result;
    }

    public function isNull(): bool
    {
        return $this->result === null;
    }

    public function getCode(): int
    {
        return $this->code;
    }
}
