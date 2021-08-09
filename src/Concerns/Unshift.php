<?php

namespace ArtARTs36\ShellCommand\Concerns;

use ArtARTs36\ShellCommand\Interfaces\ShellCommandInterface;
use ArtARTs36\ShellCommand\Settings\JoinAnd;

trait Unshift
{
    private $unshift = [];

    private $unshiftMode = false;

    public function unshift(\Closure $closure, bool $and = false): ShellCommandInterface
    {
        $this->unshiftMode = true;

        $closure($this);

        if ($and === true && !empty($this->settings)) {
            $this->unshift[] = new JoinAnd();
        }

        $this->settings = array_merge($this->unshift, $this->settings);

        $this->unshiftMode = false;
        $this->unshift = [];

        return $this;
    }
}
