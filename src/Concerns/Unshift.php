<?php

namespace ArtARTs36\ShellCommand\Concerns;

use ArtARTs36\ShellCommand\Interfaces\ShellCommandInterface;
use ArtARTs36\ShellCommand\Settings\Join;

trait Unshift
{
    private $unshift = [];

    private $unshiftMode = false;

    public function unshift(\Closure $closure, bool $and = false): ShellCommandInterface
    {
        $this->unshiftMode = true;

        $closure($this);

        if ($and === true && !empty($this->settings)) {
            $this->unshift[] = new Join();
        }

        $this->settings = array_merge($this->unshift, $this->settings);

        $this->unshiftMode = false;
        $this->unshift = [];

        return $this;
    }
}
