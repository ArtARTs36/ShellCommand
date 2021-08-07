<?php

namespace ArtARTs36\ShellCommand\Settings;

use ArtARTs36\ShellCommand\Interfaces\ShellSettingInterface;

class EnvVariable implements ShellSettingInterface
{
    private $key;

    private $value;

    public function __construct(string $key, string $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    public function __toString(): string
    {
        return "$this->key=$this->value";
    }
}
