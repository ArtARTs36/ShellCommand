<?php

namespace ArtARTs36\ShellCommand\Concerns;

use ArtARTs36\ShellCommand\Interfaces\JoinSetting;
use ArtARTs36\ShellCommand\Interfaces\ShellCommandInterface;
use ArtARTs36\ShellCommand\Settings\JoinAnd;
use ArtARTs36\ShellCommand\Settings\CommandSub;
use ArtARTs36\ShellCommand\Settings\JoinAnyway;
use ArtARTs36\ShellCommand\Settings\JoinOr;

trait HasSubCommands
{
    /** @var array<\Stringable> */
    private $join = [];

    public function joinAnd(ShellCommandInterface $command, bool $selfScope = false): ShellCommandInterface
    {
        $this->addJoin($command, new JoinAnd(), $selfScope);

        return $this;
    }

    public function joinAnyway(ShellCommandInterface $command, bool $selfScope = false): ShellCommandInterface
    {
        $this->addJoin($command, new JoinAnyway(), $selfScope);

        return $this;
    }

    public function joinOr(ShellCommandInterface $command, bool $selfScope = false): ShellCommandInterface
    {
        $this->addJoin($command, new JoinOr(), $selfScope);

        return $this;
    }

    protected function addJoin(ShellCommandInterface $command, JoinSetting $join, bool $selfScope): void
    {
        if ($selfScope) {
            $this->addSetting($join);
            $this->addSetting(new CommandSub($command));
        } else {
            $this->join[] = $join;
            $this->join[] = $command;
        }
    }

    protected function buildJoinCommands(): string
    {
        return implode(' ', $this->join);
    }
}
