<?php

namespace ArtARTs36\ShellCommand\Concerns;

use ArtARTs36\ShellCommand\Interfaces\ShellCommandInterface;
use ArtARTs36\ShellCommand\Settings\Join;
use ArtARTs36\ShellCommand\Settings\CommandSub;

trait HasSubCommands
{
    /** @var array<\Stringable> */
    private $join = [];

    public function join(ShellCommandInterface $command, bool $selfScope = false): ShellCommandInterface
    {
        if ($selfScope) {
            $this->addAmpersands();
            $this->addSetting(new CommandSub($command));
        } else {
            $this->join[] = new Join();
            $this->join[] = $command;
        }

        return $this;
    }

    protected function buildJoinCommands(): string
    {
        return implode(' ', $this->join);
    }
}
