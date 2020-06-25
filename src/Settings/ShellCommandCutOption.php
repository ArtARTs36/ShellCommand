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
}
