<?php

namespace ArtARTs36\ShellCommand;

use ArtARTs36\ShellCommand\Executors\ProcOpenExecutor;
use ArtARTs36\ShellCommand\Interfaces\CommandBuilder;
use ArtARTs36\ShellCommand\Interfaces\ShellCommandExecutor;
use ArtARTs36\ShellCommand\Interfaces\ShellCommandInterface;
use ArtARTs36\ShellCommand\Result\CommandResult;

class ShellCommander implements CommandBuilder
{
    protected $parser;

    public function __construct(?CommandRawParser $parser = null)
    {
        $this->parser = $parser ?? new CommandRawParser();
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
        return new ShellCommand($bin);
    }

    public function fromRaw(string $command): ShellCommandInterface
    {
        return $this->parser->createCommand($command);
    }
}
