<?php

namespace ArtARTs36\ShellCommand\Settings;

use ArtARTs36\ShellCommand\Interfaces\ShellSettingInterface;

class Option implements ShellSettingInterface
{
    protected static $prefix = '--';

    protected $option;

    protected $value;

    public function __construct(string $option, ?string $value = null)
    {
        $this->option = $option;
        $this->value = $value;
    }

    public function __toString(): string
    {
        return '--'. $this->option . ($this->value ? '=' . $this->value : '');
    }

    public static function is(string $raw): bool
    {
        $prefixLength = mb_strlen(static::$prefix);
        $position = mb_strpos($raw, static::$prefix);
        $nextSymbol = mb_substr($raw, $prefixLength, 1);

        return $position === 0 && $nextSymbol !== '-';
    }

    public static function isWithValue(string $raw): bool
    {
        return static::is($raw) && strpos($raw, '=') !== false;
    }

    /**
     * @return array<string>
     */
    public static function explodeAttributesFromRaw(string $raw): array
    {
        $raw = mb_strcut($raw, mb_strlen(static::$prefix), mb_strlen($raw));

        return explode('=', $raw);
    }
}
