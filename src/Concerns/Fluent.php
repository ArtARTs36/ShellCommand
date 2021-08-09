<?php

namespace ArtARTs36\ShellCommand\Concerns;

use ArtARTs36\ShellCommand\Interfaces\ShellCommandInterface;

trait Fluent
{
    public function when(bool $condition, \Closure $value): ShellCommandInterface
    {
        if ($condition === true) {
            $value($this);
        }

        return $this;
    }
}
