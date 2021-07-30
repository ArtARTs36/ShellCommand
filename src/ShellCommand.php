<?php

namespace ArtARTs36\ShellCommand;

use ArtARTs36\ShellCommand\Exceptions\CommandFailed;
use ArtARTs36\ShellCommand\Exceptions\ResultExceptionHandler;
use ArtARTs36\ShellCommand\Interfaces\ShellCommandInterface;
use ArtARTs36\ShellCommand\Interfaces\ShellSettingInterface;
use ArtARTs36\ShellCommand\Result\CommandResult;
use ArtARTs36\ShellCommand\Settings\ShellCommandJoin;
use ArtARTs36\ShellCommand\Settings\ShellCommandCutOption;
use ArtARTs36\ShellCommand\Settings\ShellCommandOption;
use ArtARTs36\ShellCommand\Settings\ShellCommandParameter;
use ArtARTs36\ShellCommand\Support\HasSubCommands;
use ArtARTs36\ShellCommand\Support\Unshift;

class ShellCommand implements ShellCommandInterface
{
    use Unshift;
    use HasSubCommands;

    public const NAVIGATE_TO_DIR = 'cd';

    private $executor;

    /** @var ShellSettingInterface[] */
    private $settings = [];

    private $inBackground = false;

    private $outputFlow;

    private $errorFlow;

    private $exceptions;

    public function __construct(string $executor, ?ResultExceptionHandler $exceptions = null)
    {
        $this->executor = $executor;
        $this->exceptions = $exceptions ?? new ResultExceptionHandler();
    }

    public static function withNavigateToDir(string $dir, string $executor): ShellCommand
    {
        return (new static(static::NAVIGATE_TO_DIR . ' ' . realpath($dir)))
            ->addAmpersands()
            ->addParameter($executor);
    }

    public static function make(string $executor = ''): ShellCommandInterface
    {
        return new static($executor);
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
     * @throws CommandFailed
     */
    public function executeOrFail(): CommandResult
    {
        $result = $this->execute();

        $this->exceptions->handle($result);

        return $result;
    }

    /**
     * Добавить параметр в командную строку
     * @return $this
     */
    public function addParameter($value): ShellCommandInterface
    {
        $this->addSetting(new ShellCommandParameter($value));

        return $this;
    }

    /**
     * Добавить амперсанды в командную строку
     * @return $this
     */
    public function addAmpersands(): ShellCommandInterface
    {
        $this->addSetting(new ShellCommandJoin());

        return $this;
    }

    /**
     * Добавить параметры в командную строку
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
     * @return $this
     */
    public function addOption(string $option): ShellCommandInterface
    {
        $this->addSetting(new ShellCommandOption($option));

        return $this;
    }

    /**
     * Добавить опцию в командную строку
     * @return $this
     */
    public function addCutOption(string $option): ShellCommandInterface
    {
        $this->addSetting(new ShellCommandCutOption($option));

        return $this;
    }

    public function addCutOptionWithValue(string $option, string $value): ShellCommandInterface
    {
        $this->addSetting(new ShellCommandCutOption($option, $value));

        return $this;
    }

    /**
     * Добавить опцию со значением
     */
    public function addOptionWithValue(string $option, string $value): ShellCommandInterface
    {
        $this->addSetting(new ShellCommandOption($option, $value));

        return $this;
    }

    public function when(bool $condition, \Closure $value): ShellCommandInterface
    {
        if ($condition === true) {
            $value($this);
        }

        return $this;
    }

    private function prepareShellCommand(): string
    {
        $cmd = implode(' ', array_merge([$this->getExecutor()], array_map('strval', $this->settings)));

        $cmd = trim($cmd);

        if (empty($cmd)) {
            return '';
        }

        $cmd = $this->addFlowIntoCommand($cmd);

        $joined = $this->buildJoinCommands();

        if ($joined !== '') {
            $cmd .= $joined;
        }

        return $cmd;
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
