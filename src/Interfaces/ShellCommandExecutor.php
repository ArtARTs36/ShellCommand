<?php

namespace ArtARTs36\ShellCommand\Interfaces;

use ArtARTs36\ShellCommand\Result\CommandResult;

interface ShellCommandExecutor
{
    public function execute(ShellCommandInterface $command): CommandResult;
}
