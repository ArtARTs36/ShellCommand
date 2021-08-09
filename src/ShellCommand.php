<?php

namespace ArtARTs36\ShellCommand;

use ArtARTs36\ShellCommand\Concerns\HasSettings;
use ArtARTs36\ShellCommand\Concerns\HasSubCommands;
use ArtARTs36\ShellCommand\Concerns\Unshift;
use ArtARTs36\ShellCommand\Exceptions\CommandFailed;
use ArtARTs36\ShellCommand\Exceptions\ResultExceptionTrigger;
use ArtARTs36\ShellCommand\Concerns\Fluent;
use ArtARTs36\ShellCommand\Concerns\HasEnvVariables;
use ArtARTs36\ShellCommand\Concerns\HasExecutor;
use ArtARTs36\ShellCommand\Concerns\HasFlows;
use ArtARTs36\ShellCommand\Executors\ProcOpenExecutor;
use ArtARTs36\ShellCommand\Interfaces\ShellCommandExecutor;
use ArtARTs36\ShellCommand\Interfaces\ShellCommandInterface;
use ArtARTs36\ShellCommand\Settings\Pipe;
use ArtARTs36\ShellCommand\Result\CommandResult;

class ShellCommand implements ShellCommandInterface
{
    use Unshift;
    use HasFlows;
    use HasEnvVariables;
    use HasSettings;
    use Fluent;
    use HasExecutor;
    use HasSubCommands;

    public const NAVIGATE_TO_DIR = 'cd';

    private $bin;

    private $isExecuted = false;

    private $inBackground = false;

    private $exceptions;

    public function __construct(string $bin, ?ShellCommandExecutor $executor = null)
    {
        $this->bin = $bin;
        $this->setExecutor($executor ?? new ProcOpenExecutor());
    }

    public static function withNavigateToDir(string $dir, string $executor): ShellCommandInterface
    {
        $real = realpath($dir);
        $to = $real === false ? $dir : $real;

        return (new static(static::NAVIGATE_TO_DIR . ' ' . $to))
            ->addAmpersands()
            ->addArgument($executor);
    }

    public static function make(string $bin = ''): ShellCommandInterface
    {
        return new static($bin);
    }

    /**
     * Выполнить программу
     */
    public function execute(): CommandResult
    {
        $line = $this->prepareShellCommand();
        $result = [];
        $code = null;

        exec($line, $result, $code);

        return new CommandResult($line, $result ? $result[0] : null, new \DateTime(), $code);
    }

    /**
     * @throws CommandFailed
     */
    public function executeOrFail(): CommandResult
    {
        $result = $this->execute();

        $this->handleException($result);

        return $result;
    }

    protected function prepareShellCommand(): string
    {
        $parts = $this->buildEnvLineParts();

        $parts[] = $this->getBin();
        array_push($parts, ... $this->buildSettingsLineParts());

        $cmd = implode(' ', $parts);

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

    public function addPipe(): ShellCommandInterface
    {
        $last = $this->getLastSetting();

        if ($last === null || $last instanceof Pipe) {
            throw new \LogicException('Command is empty or last setting is pipe');
        }

        $this->addSetting(new Pipe());

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

        if ($this->errorFlow !== false) {
            $command .= ' ' . $this->parseFlow(FlowType::STDERR, $this->getErrorFlow());
        }

        return $this->inBackground ? $command . ' &' : $command;
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

    private function handleException(CommandResult $result): void
    {
        $this->exceptions = $this->exceptions ?? new ResultExceptionTrigger();

        $this->exceptions->handle($result);
    }
}
