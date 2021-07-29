<?php

namespace ArtARTs36\ShellCommand;

class ExecuteResult
{
    protected $commandLine;

    protected $result;

    protected $date;

    protected $code;

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
}
