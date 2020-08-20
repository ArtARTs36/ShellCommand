<?php

namespace ArtARTs36\ShellCommand\Settings;

use ArtARTs36\ShellCommand\Interfaces\ShellSettingInterface;

/**
 * Class ShellCommandOption
 * @package ArtARTs36\ShellCommand\Settings
 */
class ShellCommandOption implements ShellSettingInterface
{
    /** @var string */
    protected $option;

    /**
     * @var string
     */
    protected $value;

    /**
     * ShellCommandParameter constructor.
     * @param string $option
     * @param string|null $value
     */
    public function __construct(string $option, string $value = null)
    {
        $this->option = $option;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return '--'. $this->option . ($this->value ? '=' . $this->value : '');
    }

    /**
     * @param string $raw
     * @return bool
     */
    public static function is(string $raw): bool
    {
        return is_int(strpos($raw, '--', 0));
    }

    /**
     * @param string $raw
     * @return bool
     */
    public static function isWithValue(string $raw): bool
    {
        return static::is($raw) && strpos($raw, '=') !== false;
    }

    /**
     * @param string $raw
     * @return array
     */
    public static function explodeAttributesFromRaw(string $raw): array
    {
        $raw = mb_strcut($raw, 2, mb_strlen($raw));

        return explode('=', $raw);
    }
}
