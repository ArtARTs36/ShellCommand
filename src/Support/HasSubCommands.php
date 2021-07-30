<?php

namespace ArtARTs36\ShellCommand\Support;

use ArtARTs36\ShellCommand\Interfaces\ShellCommandInterface;
use ArtARTs36\ShellCommand\Settings\ShellCommandJoin;
use ArtARTs36\ShellCommand\Settings\ShellCommandParameter;
use ArtARTs36\ShellCommand\Settings\ShellCommandSub;

trait HasSubCommands
{
    /** @var array<\Stringable> */
    private $join = [];

    public function join(ShellCommandInterface $command, bool $selfScope = false): ShellCommandInterface
    {
        if ($selfScope) {
            $this->addAmpersands();
            $this->addSetting(new ShellCommandSub($command));
        } else {
            $this->join[] = new ShellCommandJoin();
            $this->join[] = $command;
        }

        return $this;
    }

    protected function buildJoinCommands(): string
    {
        return implode(' ', $this->join);
    }
}
