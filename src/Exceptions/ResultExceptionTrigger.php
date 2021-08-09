<?php

namespace ArtARTs36\ShellCommand\Exceptions;

use ArtARTs36\ShellCommand\Result\CommandResult;
use ArtARTs36\ShellCommand\Result\ResultCode;

class ResultExceptionTrigger
{
    /** @var <string, class-string<CommandFailed> */
    protected $map = [
        ResultCode::IS_NOT_EXECUTABLE => CommandIsNotExecutable::class,
        ResultCode::COMMAND_NOT_FOUND => CommandNotFound::class,
        ResultCode::GENERAL_ERRORS => CommandHasGeneralErrors::class,
    ];

    /**
     * @throws CommandFailed
     */
    public function handle(CommandResult $result): void
    {
        if (! $this->isFailed($result)) {
            return;
        }

        $class = $this->map[$result->getCode()];

        throw new $class($result);
    }

    public function isFailed(CommandResult $result): bool
    {
        return array_key_exists($result->getCode(), $this->map);
    }
}