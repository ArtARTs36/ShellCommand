<?php

namespace ArtARTs36\ShellCommand\Settings;

use ArtARTs36\ShellCommand\Interfaces\ShellSettingInterface;

class Pipe implements ShellSettingInterface
{
    public function __toString(): string
    {
        return '|';
    }
}
