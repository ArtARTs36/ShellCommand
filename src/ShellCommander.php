<?php

namespace ArtARTs36\ShellCommand;

use ArtARTs36\ShellCommand\Interfaces\Commander;
use ArtARTs36\ShellCommand\Interfaces\ShellCommandExecutor;
use ArtARTs36\ShellCommand\Interfaces\ShellCommandInterface;

class ShellCommander implements Commander
{
    protected $executor;

    protected $parser;

    public function __construct(?ShellCommandExecutor $executor = null, ?CommandRawParser $parser = null)
    {
        $this->executor = $executor;
        $this->parser = $parser;
    }

    public function navigateToDir(string $dir, string $bin): ShellCommandInterface
    {
        return ShellCommand::withNavigateToDir($dir, $bin)->setExecutor($this->executor);
    }

    public function make(string $bin = ''): ShellCommandInterface
    {
        return new ShellCommand($bin, $this->executor);
    }

    public function fromRaw(string $command): ShellCommandInterface
    {
        return $this->parser->createCommand($command);
    }
}
