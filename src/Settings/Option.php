<?php

namespace ArtARTs36\ShellCommand\Settings;

use ArtARTs36\ShellCommand\Interfaces\ShellSettingInterface;

class Option implements ShellSettingInterface
{
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
        return mb_strpos($raw, '--') === 0;
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
        $raw = mb_strcut($raw, 2, mb_strlen($raw));

        return explode('=', $raw);
    }
}
