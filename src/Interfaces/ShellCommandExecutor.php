<?php

namespace ArtARTs36\ShellCommand\Interfaces;

use ArtARTs36\ShellCommand\Result\CommandResult;

/**
 * Executor for shell commands.
 */
interface ShellCommandExecutor
{
    public function execute(ShellCommandInterface $command): CommandResult;
}
