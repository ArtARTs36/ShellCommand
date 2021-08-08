<?php

namespace ArtARTs36\ShellCommand\Concerns;

use ArtARTs36\ShellCommand\Interfaces\ShellCommandExecutor;
use ArtARTs36\ShellCommand\Interfaces\ShellCommandInterface;

trait HasExecutor
{
    private $executor;

    public function setExecutor(ShellCommandExecutor $executor): ShellCommandInterface
    {
        $this->executor = $executor;

        return $this;
    }
}
