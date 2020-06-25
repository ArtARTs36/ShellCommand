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
}
