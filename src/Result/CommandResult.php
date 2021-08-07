<?php

namespace ArtARTs36\ShellCommand\Result;

final class CommandResult
{
    private $commandLine;

    private $stdout;

    private $date;

    private $stderr;

    public function __construct(
        string $commandLine,
        ?string $result,
        \DateTimeInterface $date,
        ?string $stderr = null
    ) {
        $this->commandLine = $commandLine;
        $this->stdout = $result;
        $this->date = $date;
        $this->stderr = $stderr;
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
        return $this->stdout;
    }

    /**
     * @return string|null - from stderr
     */
    public function getError(): ?string
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

    public function __toString()
    {
        return $this->stdout;
    }
}
