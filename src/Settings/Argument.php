<?php

namespace ArtARTs36\ShellCommand\Settings;

use ArtARTs36\ShellCommand\Interfaces\ShellSettingInterface;

class Argument implements ShellSettingInterface
{
    private $string;

    private $escape;

    public function __construct(string $string, bool $escape = true)
    {
        $this->string = $string;
        $this->escape = $escape;
    }

    public function __toString(): string
    {
        return $this->escape ? escapeshellarg($this->string) : $this->string;
    }

    public static function is(string $raw): bool
    {
        return strpos($raw, '-', 0) !== 0;
    }
}
