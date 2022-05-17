<?php

namespace ArtARTs36\ShellCommand;

use ArtARTs36\ShellCommand\Interfaces\CommandBuilder;
use ArtARTs36\ShellCommand\Interfaces\ShellCommandInterface;

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

        $cmd = $this
            ->make(ShellCommand::NAVIGATE_TO_DIR)
            ->addArgument($to)
            ->addAmpersands();

        foreach (explode(' ', $bin) as $binPart) {
            $cmd->addArgument($binPart);
        }

        return $cmd;
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
