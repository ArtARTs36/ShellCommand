<?php

namespace ArtARTs36\ShellCommand\Exceptions;

use ArtARTs36\ShellCommand\Interfaces\ExceptionTrigger;
use ArtARTs36\ShellCommand\Result\CommandResult;

class UserExceptionTrigger implements ExceptionTrigger
{
    protected $trigger;

    protected $callbacks;

    public function __construct(ResultExceptionTrigger $trigger, array $callbacks)
    {
        $this->trigger = $trigger;
        $this->callbacks = $callbacks;
    }

    public static function fromCallbacks(array $callbacks): self
    {
        return new static(new ResultExceptionTrigger(), $callbacks);
    }

    public function handle(CommandResult $result): void
    {
        foreach ($this->callbacks as $callback) {
            $callback($result);
        }

        $this->trigger->handle($result);
    }
}
