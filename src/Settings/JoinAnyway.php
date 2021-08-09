<?php

namespace ArtARTs36\ShellCommand\Settings;

use ArtARTs36\ShellCommand\Interfaces\JoinSetting;

class JoinAnyway implements JoinSetting
{
    public function __toString(): string
    {
        return ';';
    }
}
