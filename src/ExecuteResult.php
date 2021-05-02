<?php

namespace ArtARTs36\ShellCommand;

class ExecuteResult
{
    protected $commandLine;

    protected $result;

    protected $date;

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
}
