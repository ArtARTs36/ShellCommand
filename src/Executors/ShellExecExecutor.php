<?php

namespace ArtARTs36\ShellCommand\Executors;

use ArtARTs36\ShellCommand\Interfaces\ShellCommandExecutor;
use ArtARTs36\ShellCommand\Interfaces\ShellCommandInterface;
use ArtARTs36\ShellCommand\Result\CommandResult;

class ShellExecExecutor implements ShellCommandExecutor
{
    public function execute(ShellCommandInterface $command): CommandResult
    {
        return new CommandResult($command, shell_exec($command), new \DateTime());
    }
}
