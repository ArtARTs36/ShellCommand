<?php

namespace ArtARTs36\ShellCommand;

use ArtARTs36\ShellCommand\Interfaces\ShellCommandInterface;
use ArtARTs36\ShellCommand\Interfaces\ShellSettingInterface;
use ArtARTs36\ShellCommand\Settings\ShellCommandCutOption;
use ArtARTs36\ShellCommand\Settings\ShellCommandOption;
use ArtARTs36\ShellCommand\Settings\ShellCommandParameter;
use ArtARTs36\ShellCommand\Support\Unshift;

class ShellCommand implements ShellCommandInterface
{
    use Unshift;

    public const MOVE_DIR = 'cd';

    /** @var string */
    private $executor;

    /** @var bool */
    private $isExecuted = false;

    /** @var string */
    private $shellResult = null;

    /** @var ShellSettingInterface[] */
    private $settings = [];

    /**
     * ShellCommand constructor.
     * @param string $executor
     */
    public function __construct(string $executor)
    {
        $this->executor = $executor;
    }

    /**
     * @param string $dir
     * @param string $executor
     * @return ShellCommand
     */
    public static function getInstanceWithMoveDir(string $dir, string $executor): ShellCommand
    {
        return (new self(static::MOVE_DIR . ' ' . realpath($dir)))
            ->addAmpersands()
            ->addParameter($executor);
    }

    /**
     * Выполнить программу
     *
     * @return self
     */
    public function execute(): ShellCommandInterface
    {
        $this->isExecuted = true;

        $this->shellResult = shell_exec($this->prepareShellCommand());

        return $this;
    }

    /**
     * Добавить параметр в командную строку
     *
     * @param mixed $value
     * @param bool $quotes
     * @return $this
     */
    public function addParameter($value, bool $quotes = false): ShellCommandInterface
    {
        $this->addSetting(new ShellCommandParameter($value, $quotes));

        return $this;
    }

    /**
     * Добавить амперсанды в командную строку
     * @return $this
     */
    public function addAmpersands(): ShellCommandInterface
    {
        $this->addSetting(new ShellCommandParameter('&&'));

        return $this;
    }

    /**
     * Добавить параметры в командную строку
     *
     * @param array $values
     * @return $this
     */
    public function addParameters(array $values): ShellCommandInterface
    {
        foreach ($values as $value) {
            $this->addSetting(new ShellCommandParameter($value));
        }

        return $this;
    }

    /**
     * Добавить опцию в командную строку
     *
     * @param mixed $option
     * @return $this
     */
    public function addOption($option): ShellCommandInterface
    {
        $this->addSetting(new ShellCommandOption($option));

        return $this;
    }

    /**
     * Добавить опцию в командную строку
     *
     * @param string $option
     * @return $this
     */
    public function addCutOption($option): ShellCommandInterface
    {
        $this->addSetting(new ShellCommandCutOption($option));

        return $this;
    }

    /**
     * @param string $option
     * @param mixed $value
     * @return ShellCommand
     */
    public function addCutOptionWithValue($option, $value): ShellCommandInterface
    {
        $this->addSetting(new ShellCommandCutOption($option, $value));

        return $this;
    }

    /**
     * Добавить опцию со значением
     *
     * @param string $option
     * @param string $value
     * @return $this
     */
    public function addOptionWithValue(string $option, string $value): ShellCommandInterface
    {
        $this->addSetting(new ShellCommandOption($option, $value));

        return $this;
    }

    /**
     * @param bool $condition
     * @param \Closure $value
     * @return ShellCommandInterface
     */
    public function when(bool $condition, \Closure $value): ShellCommandInterface
    {
        if ($condition === true) {
            $value($this);
        }

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
        $cmd = implode(' ', array_merge([$this->getExecutor()], array_map('strval', $this->settings)));

        $cmd = trim($cmd);

        if (empty($cmd)) {
            return '';
        }

        return $cmd . ' 2>&1';
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

    /**
     * @param ShellSettingInterface $setting
     * @return ShellCommandInterface
     */
    private function addSetting(ShellSettingInterface $setting): ShellCommandInterface
    {
        if ($this->unshiftMode === true) {
            $this->unshift[] = $setting;
        } else {
            $this->settings[] = $setting;
        }

        return $this;
    }

    /**
     * @return string
     */
    private function getExecutor(): string
    {
        if ($this->executor === '') {
            return '';
        }

        return ($real = realpath($this->executor)) ? $real : $this->executor;
    }
}
