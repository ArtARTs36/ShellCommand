<?php

namespace ArtARTs36\ShellCommand\Settings;

use ArtARTs36\ShellCommand\Interfaces\ShellSettingInterface;

/**
 * Class ShellCommandParameter
 * @package ArtARTs36\ShellCommand\Settings
 */
class ShellCommandParameter implements ShellSettingInterface
{
    /** @var string */
    private $string;

    /** @var bool */
    private $quotes = false;

    /**
     * ShellCommandParameter constructor.
     * @param string $string
     * @param bool $quotes
     */
    public function __construct(string $string, bool $quotes = false)
    {
        $this->string = $string;
        $this->quotes = $quotes;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->quotes ? ('"' . $this->string . '"') : $this->string;
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
