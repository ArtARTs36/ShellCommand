<?php

namespace ArtARTs36\ShellCommand;

use ArtARTs36\ShellCommand\Interfaces\ShellSettingInterface;
use ArtARTs36\ShellCommand\Settings\ShellCommandCutOption;
use ArtARTs36\ShellCommand\Settings\ShellCommandOption;
use ArtARTs36\ShellCommand\Settings\ShellCommandParameter;

class ShellCommand
{
    /** @var string */
    private $executor;

    /** @var bool */
    private $isExecuted = false;

    /** @var string */
    private $shellResult = null;

    /** @var ShellSettingInterface[] */
    private $settings = [];

    /**
     * @var bool
     */
    private $isCheckRealpathExecutor;

    public function __construct(string $executor, bool $isCheckRealpathExecutor = true)
    {
        $this->executor = $executor;
        $this->isCheckRealpathExecutor = $isCheckRealpathExecutor;
    }

    /**
     * @param string $executor
     * @param bool $isCheckRealpathExecutor
     * @return ShellCommand
     */
    public static function getInstance(string $executor, bool $isCheckRealpathExecutor = true): ShellCommand
    {
        return new self($executor, $isCheckRealpathExecutor);
    }

    /**
     * @param string $dir
     * @param string $executor
     * @param bool $isCheckRealpathExecutor
     * @return ShellCommand
     */
    public static function getInstanceWithCd(
        string $dir,
        string $executor,
        bool $isCheckRealpathExecutor = true
    ): ShellCommand {
        return (new self('cd '. $dir, $isCheckRealpathExecutor))
            ->addParameter($executor)
            ->addAmpersands();
    }

    /**
     * Выполнить программу
     *
     * @return self
     */
    public function execute(): self
    {
        $this->isExecuted = true;

        $this->shellResult = shell_exec($this->prepareShellCommand());

        return $this;
    }

    /**
     * Добавить параметр в командную строку
     *
     * @param mixed $value
     * @return $this
     */
    public function addParameter($value): self
    {
        $this->settings[] = new ShellCommandParameter($value);

        return $this;
    }

    /**
     * Добавить амперсанды в командную строку
     * @return $this
     */
    public function addAmpersands(): self
    {
        $this->settings[] = new ShellCommandParameter('&&');

        return $this;
    }

    /**
     * Добавить параметры в командную строку
     *
     * @param array $values
     * @return $this
     */
    public function addParameters(array $values): self
    {
        foreach ($values as $value) {
            $this->settings[] = new ShellCommandParameter($value);
        }

        return $this;
    }

    /**
     * Добавить опцию в командную строку
     *
     * @param mixed $option
     * @return $this
     */
    public function addOption($option): self
    {
        $this->settings[] = new ShellCommandOption($option);

        return $this;
    }

    /**
     * Добавить опцию в командную строку
     *
     * @param string $option
     * @return $this
     */
    public function addCutOption($option): self
    {
        $this->settings[] = new ShellCommandCutOption($option);

        return $this;
    }

    /**
     * Добавить опцию со значением
     *
     * @param string $option
     * @param string $value
     * @return $this
     */
    public function addOptionWithValue(string $option, string $value): self
    {
        $this->settings[] = new ShellCommandOption($option, $value);

        return $this;
    }

    /**
     * Получить результат выполнения программы
     *
     * @return string|null
     */
    public function getShellResult()
    {
        if ($this->shellResult === null && $this->isExecuted === false) {
            $this->execute();
        }

        return $this->shellResult;
    }

    /**
     * Подготовить шелл-команду
     *
     * @return string
     */
    private function prepareShellCommand(): string
    {
        $settings = array_map(function (ShellSettingInterface $setting) {
            return $setting->__toString();
        }, $this->settings);

        return implode(' ', array_merge(
            [$this->isCheckRealpathExecutor ? realpath($this->executor) : $this->executor],
            $settings
        ));
    }

    /**
     * @return bool
     */
    public function isExecuted(): bool
    {
        return $this->isExecuted;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->prepareShellCommand();
    }
}
