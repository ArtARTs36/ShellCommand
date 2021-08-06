<?php

namespace ArtARTs36\ShellCommand\Result;

final class CommandResult
{
    private $commandLine;

    private $result;

    private $date;

    public function __construct(string $commandLine, ?string $result, \DateTimeInterface $date)
    {
        $this->commandLine = $commandLine;
        $this->result = $result;
        $this->date = $date;
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
}
