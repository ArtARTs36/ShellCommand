<?php

namespace ArtARTs36\ShellCommand;

use ArtARTs36\ShellCommand\Interfaces\ShellCommandInterface;
use ArtARTs36\ShellCommand\Interfaces\ShellSettingInterface;
use ArtARTs36\ShellCommand\Result\CommandResult;
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

    /** @var bool */
    private $inBackground = false;

    private $outputFlow;

    private $errorFlow;

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
     */
    public function execute(): CommandResult
    {
        $line = $this->prepareShellCommand();
        $result = null;
        $code = null;

        exec($line, $result, $code);

        return new CommandResult($line, shell_exec($line), new \DateTime(), $code);
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

        return $this->addFlowIntoCommand($cmd);
    }

    public function getErrorFlow(): string
    {
        if ($this->inBackground && ! $this->errorFlow) {
            return '/dev/null';
        }

        return $this->errorFlow ?? '&'. FlowType::STDOUT;
    }

    public function getOutputFlow(): ?string
    {
        return $this->outputFlow;
    }

    public function setOutputFlow(string $output): ShellCommandInterface
    {
        $this->outputFlow = $output;

        return $this;
    }

    public function setErrorFlow(string $error): ShellCommandInterface
    {
        $this->errorFlow = $error;

        return $this;
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

        $command .= ' ' . $this->parseFlow(FlowType::STDERR, $this->getErrorFlow());

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
    protected function getExecutor(): string
    {
        if ($this->executor === '') {
            return '';
        }

        return ($real = realpath($this->executor)) ? $real : $this->executor;
    }
}
