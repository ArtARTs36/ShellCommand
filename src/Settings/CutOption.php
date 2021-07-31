<?php

namespace ArtARTs36\ShellCommand\Settings;

class CutOption extends Option
{
    protected static $prefix = '-';

    public function __toString(): string
    {
        return '-'. $this->option . ($this->value ? '=' . $this->value : '');
    }
}
