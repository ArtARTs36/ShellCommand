<?php

namespace ArtARTs36\ShellCommand\Exceptions;

use ArtARTs36\ShellCommand\Result\CommandResult;

class CommandFailed extends \RuntimeException
{
    public $commandResult;

    public function __construct(CommandResult $result)
    {
        $this->commandResult = $result;

        parent::__construct($this->prepareMessage());
    }

    protected function prepareMessage(): string
    {
        return sprintf(
            'Stdout: %s. Stderr: %s',
            $this->commandResult->getResult(),
            $this->commandResult->getError()
        );
    }
}
