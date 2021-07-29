<?php

namespace ArtARTs36\ShellCommand\Settings;

use ArtARTs36\ShellCommand\Interfaces\ShellCommandInterface;
use ArtARTs36\ShellCommand\Interfaces\ShellSettingInterface;

class ShellCommandSub implements ShellSettingInterface
{
    private $command;

    public function __construct(ShellCommandInterface $command)
    {
        $this->command = $command;
    }

    public function __toString(): string
    {
        return $this->command->__toString();
    }
}
