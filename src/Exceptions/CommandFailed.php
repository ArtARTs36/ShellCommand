<?php

namespace ArtARTs36\ShellCommand\Exceptions;

use ArtARTs36\ShellCommand\Result\CommandResult;

class CommandFailed extends \RuntimeException
{
    public $commandResult;

    public function __construct(CommandResult $result)
    {
        $this->commandResult = $result;

        parent::__construct($result->getResult());
    }
}
