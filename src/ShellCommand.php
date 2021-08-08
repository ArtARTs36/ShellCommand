<?php

namespace ArtARTs36\ShellCommand;

use ArtARTs36\ShellCommand\Concerns\HasEnvVariables;
use ArtARTs36\ShellCommand\Concerns\HasFlows;
use ArtARTs36\ShellCommand\Executors\ProcOpenExecutor;
use ArtARTs36\ShellCommand\Interfaces\ShellCommandExecutor;
use ArtARTs36\ShellCommand\Interfaces\ShellCommandInterface;
use ArtARTs36\ShellCommand\Interfaces\ShellSettingInterface;
use ArtARTs36\ShellCommand\Settings\ShellCommandCutOption;
use ArtARTs36\ShellCommand\Settings\ShellCommandOption;
use ArtARTs36\ShellCommand\Settings\ShellCommandParameter;
use ArtARTs36\ShellCommand\Support\Unshift;

class ShellCommand implements ShellCommandInterface
{
    use Unshift;
    use HasFlows;
    use HasEnvVariables;

    public const NAVIGATE_TO_DIR = 'cd';
    public const MOVE_DIR = self::NAVIGATE_TO_DIR;

    private $bin;

    private $isExecuted = false;

    private $shellResult = null;

    /** @var ShellSettingInterface[] */
    private $settings = [];

    private $inBackground = false;

    private $executor;

    public function __construct(string $bin, ?ShellCommandExecutor $executor = null)
    {
        $this->bin = $bin;
        $this->executor = $executor ?? new ProcOpenExecutor();
    }

    /**
     * @deprecated
     */
    public static function getInstanceWithMoveDir(string $dir, string $bin): ShellCommandInterface
    {
        trigger_error(
            'Method ShellCommandInterface::getInstanceWithMoveDir is deprecated.' .
            'Will be removed in v. 2.0' .
            'Should use ShellCommandInterface::withNavigateToDir',
            E_USER_DEPRECATED
        );

        return static::withNavigateToDir($dir, $bin);
    }

    public static function withNavigateToDir(string $dir, string $executor): ShellCommandInterface
    {
        $real = realpath($dir);
        $to = $real === false ? $dir : $real;

        return (new static(static::NAVIGATE_TO_DIR . ' ' . $to))
            ->addAmpersands()
            ->addParameter($executor);
    }

    public static function make(string $executor = ''): ShellCommandInterface
    {
        return new static($executor);
    }

    public function setExecutor(ShellCommandExecutor $executor): ShellCommandInterface
    {
        $this->executor = $executor;

        return $this;
    }

    /**
     * Выполнить программу
     *
     * @return self
     */
    public function execute(): ShellCommandInterface
    {
        $this->isExecuted = true;

        $this->shellResult = $this->executor->execute($this);

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
        $parts = [];

        if (count($this->env) > 0) {
            $parts[] = "export";
            array_push($parts, ...array_values(array_map('strval', $this->env)));
            $parts[] = '&&';
        }

        $parts[] = $this->getBin();
        array_push($parts, ... array_map('strval', $this->settings));

        $cmd = implode(' ', $parts);

        $cmd = trim($cmd);

        if (empty($cmd)) {
            return '';
        }

        return $this->addFlowIntoCommand($cmd);
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

    public function inBackground(): ShellCommandInterface
    {
        $this->inBackground = true;

        return $this;
    }

    protected function addFlowIntoCommand(string $command): string
    {
        if ($this->getOutputFlow()) {
            $command .= ' '. $this->parseFlow(FlowType::STDOUT, $this->getOutputFlow());
        }

        if ($this->errorFlow !== false) {
            $command .= ' ' . $this->parseFlow(FlowType::STDERR, $this->getErrorFlow());
        }

        return $this->inBackground ? $command . ' &' : $command;
    }

    protected function parseFlow(int $type, string $value): string
    {
        return $type . '>'. $value;
    }

    /**
     * @param ShellSettingInterface $setting
     * @return ShellCommandInterface
     */
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
     * @return string
     */
    protected function getBin(): string
    {
        if ($this->bin === '') {
            return '';
        }

        return ($real = realpath($this->bin)) ? $real : $this->bin;
    }
}
