<?php

namespace ArtARTs36\ShellCommand\Concerns;

use ArtARTs36\ShellCommand\Interfaces\ShellCommandInterface;
use ArtARTs36\ShellCommand\Interfaces\ShellSettingInterface;
use ArtARTs36\ShellCommand\Settings\Argument;
use ArtARTs36\ShellCommand\Settings\JoinAnd;
use ArtARTs36\ShellCommand\Settings\Option;
use ArtARTs36\ShellCommand\Settings\CutOption;

trait HasSettings
{
    /** @var ShellSettingInterface[] */
    private $settings = [];

    public function getSettings(): array
    {
        return $this->settings;
    }

    public function addArgument(string $value): ShellCommandInterface
    {
        $this->addSetting(new Argument($value));

        return $this;
    }

    public function addAmpersands(): ShellCommandInterface
    {
        $this->addSetting(new JoinAnd());

        return $this;
    }

    public function addArguments(array $values): ShellCommandInterface
    {
        foreach ($values as $value) {
            $this->addSetting(new Argument($value));
        }

        return $this;
    }

    public function getLastSetting(): ?ShellSettingInterface
    {
        return end($this->settings);
    }

    protected function addSetting(ShellSettingInterface $setting): ShellCommandInterface
    {
        if ($this->unshiftMode === true) {
            $this->unshift[] = $setting;
        } else {
            $this->settings[] = $setting;
        }

        return $this;
    }

    public function addOption(string $option): ShellCommandInterface
    {
        $this->addSetting(new Option($option));

        return $this;
    }

    public function addCutOption(string $option): ShellCommandInterface
    {
        $this->addSetting(new CutOption($option));

        return $this;
    }

    public function addCutOptionWithValue(string $option, string $value): ShellCommandInterface
    {
        $this->addSetting(new CutOption($option, $value));

        return $this;
    }

    public function addOptionWithValue(string $option, string $value): ShellCommandInterface
    {
        $this->addSetting(new Option($option, $value));

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
