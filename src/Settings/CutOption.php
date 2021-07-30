<?php

namespace ArtARTs36\ShellCommand\Settings;

class CutOption extends Option
{
    public function __toString(): string
    {
        return '-'. $this->option . ($this->value ? '=' . $this->value : '');
    }

    public static function is(string $raw): bool
    {
        return is_int(strpos($raw, '-')) && !parent::is($raw);
    }

    /**
     * @return array<string>
     */
    public static function explodeAttributesFromRaw(string $raw): array
    {
        $raw = mb_strcut($raw, 1, mb_strlen($raw));

        return explode('=', $raw);
    }
}
