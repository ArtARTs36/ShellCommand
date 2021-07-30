<?php

namespace ArtARTs36\ShellCommand\Settings;

use ArtARTs36\ShellCommand\Interfaces\ShellSettingInterface;

class Join implements ShellSettingInterface
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
