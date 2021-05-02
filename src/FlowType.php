<?php

namespace ArtARTs36\ShellCommand;

class FlowType
{
    public const STDIN = 0;
    public const STDOUT = 1;
    public const STDERR = 2;

    /**
     * @return int[]
     */
    public static function cases(): array
    {
        return [
            static::STDIN,
            static::STDOUT,
            static::STDERR,
        ];
    }

    public static function is(int $value): bool
    {
        return in_array($value, static::cases());
    }
}
