<?php

namespace ArtARTs36\ShellCommand\Interfaces;

use ArtARTs36\ShellCommand\Exceptions\CommandFailed;
use ArtARTs36\ShellCommand\Result\CommandResult;

interface ExceptionTrigger
{
    /**
     * @throws CommandFailed
     */
    public function handle(CommandResult $result): void;
}
