<?php

namespace ArtARTs36\ShellCommand\Settings;

use ArtARTs36\ShellCommand\Interfaces\ShellSettingInterface;

class Argument implements ShellSettingInterface
{
    private $string;

    public function __construct(string $string)
    {
        $this->string = $string;
    }

    public function __toString(): string
    {
        return escapeshellarg($this->string);
    }

    public static function is(string $raw): bool
    {
        return strpos($raw, '-', 0) !== 0;
    }
}
