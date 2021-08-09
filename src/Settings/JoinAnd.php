<?php

namespace ArtARTs36\ShellCommand\Settings;

use ArtARTs36\ShellCommand\Interfaces\JoinSetting;

class JoinAnd implements JoinSetting
{
    public function __toString(): string
    {
        return '&&';
    }

    public static function is(string $raw): bool
    {
        return $raw === '&&';
    }
}
