<?php

namespace ArtARTs36\ShellCommand\Support;

use ArtARTs36\ShellCommand\Interfaces\ShellCommandInterface;
use ArtARTs36\ShellCommand\Settings\ShellCommandJoin;
use ArtARTs36\ShellCommand\Settings\ShellCommandParameter;

trait Unshift
{
    private $unshift = [];

    private $unshiftMode = false;

    /**
     * @param \Closure $closure
     * @param bool $and
     * @return ShellCommandInterface
     */
    public function unshift(\Closure $closure, bool $and = false): ShellCommandInterface
    {
        $this->unshiftMode = true;

        $closure($this);

        if ($and === true && !empty($this->settings)) {
            $this->unshift[] = new ShellCommandJoin();
        }

        $this->settings = array_merge($this->unshift, $this->settings);

        $this->unshiftMode = false;
        $this->unshift = [];

        return $this;
    }
}
