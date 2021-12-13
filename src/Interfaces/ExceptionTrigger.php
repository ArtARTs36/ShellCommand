<?php

namespace ArtARTs36\ShellCommand\Interfaces;

use ArtARTs36\ShellCommand\Exceptions\CommandFailed;
use ArtARTs36\ShellCommand\Result\CommandResult;

/**
 * Trigger for exceptions.
 */
interface ExceptionTrigger
{
    /**
     * @throws CommandFailed
     */
    public function handle(CommandResult $result): void;
}
