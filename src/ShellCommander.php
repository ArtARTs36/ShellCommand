<?php

namespace ArtARTs36\ShellCommand;

use ArtARTs36\ShellCommand\Executors\ProcOpenExecutor;
use ArtARTs36\ShellCommand\Interfaces\Commander;
use ArtARTs36\ShellCommand\Interfaces\ShellCommandExecutor;
use ArtARTs36\ShellCommand\Interfaces\ShellCommandInterface;
use ArtARTs36\ShellCommand\Result\CommandResult;

class ShellCommander implements Commander
{
    protected $executor;

    protected $parser;

    public function __construct(?ShellCommandExecutor $executor = null, ?CommandRawParser $parser = null)
    {
        $this->executor = $executor ?? new ProcOpenExecutor();
        $this->parser = $parser ?? new CommandRawParser();
    }

    public function useExecutor(ShellCommandExecutor $executor): self
    {
        $this->executor = $executor;

        return $this;
    }

    public function makeNavigateToDir(string $dir, string $bin): ShellCommandInterface
    {
        $real = realpath($dir);
        $to = $real === false ? $dir : $real;

        return $this
            ->make(ShellCommand::NAVIGATE_TO_DIR)
            ->addArgument($to)
            ->addAmpersands()
            ->addArgument($bin);
    }

    public function make(string $bin = ''): ShellCommandInterface
    {
        return new ShellCommand($bin, $this->executor);
    }

    public function fromRaw(string $command): ShellCommandInterface
    {
        return $this->parser->createCommand($command);
    }

    public function execute(ShellCommandInterface $command): CommandResult
    {
        return $command->execute($this->executor);
    }
}
