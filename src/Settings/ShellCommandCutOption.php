<?php

namespace ArtARTs36\ShellCommand\Settings;

/**
 * Class ShellCommandCutOption
 * @package ArtARTs36\ShellCommand\Settings
 */
class ShellCommandCutOption extends ShellCommandOption
{
    /**
     * @return string
     */
    public function __toString(): string
    {
        return '-'. $this->option . ($this->value ? '=' . $this->value : '');
    }

    /**
     * @param string $raw
     * @return bool
     */
    public static function is(string $raw): bool
    {
        return is_int(strpos($raw, '-', 0)) && !parent::is($raw);
    }

    /**
     * @param string $raw
     * @return array
     */
    public static function explodeAttributesFromRaw(string $raw): array
    {
        $raw = mb_strcut($raw, 1, mb_strlen($raw));

        return explode('=', $raw);
    }
}
