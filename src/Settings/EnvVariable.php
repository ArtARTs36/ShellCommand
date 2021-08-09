<?php

namespace ArtARTs36\ShellCommand\Settings;

class EnvVariable
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
