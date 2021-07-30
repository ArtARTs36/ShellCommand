<?php

namespace ArtARTs36\ShellCommand\Settings;

use ArtARTs36\ShellCommand\Interfaces\ShellSettingInterface;

/**
 * Class ShellCommandParameter
 * @package ArtARTs36\ShellCommand\Settings
 */
class ShellCommandParameter implements ShellSettingInterface
{
    private $string;

    /**
     * ShellCommandParameter constructor.
     * @param string $string
     */
    public function __construct(string $string)
    {
        $this->string = $string;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return escapeshellarg($this->string);
    }

    /**
     * @param string $raw
     * @return bool
     */
    public static function is(string $raw): bool
    {
        return strpos($raw, '-', 0) !== 0;
    }
}
