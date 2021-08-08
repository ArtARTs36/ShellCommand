<?php

namespace ArtARTs36\ShellCommand\Concerns;

use ArtARTs36\ShellCommand\Interfaces\ShellCommandInterface;
use ArtARTs36\ShellCommand\Interfaces\ShellSettingInterface;

trait HasSettings
{
    /** @var ShellSettingInterface[] */
    private $settings = [];

    protected function addSetting(ShellSettingInterface $setting): ShellCommandInterface
    {
        if ($this->unshiftMode === true) {
            $this->unshift[] = $setting;
        } else {
            $this->settings[] = $setting;
        }

        return $this;
    }

    /**
     * @return array<string>
     */
    protected function buildSettingsLineParts(): array
    {
        return array_map('strval', $this->settings);
    }
}
