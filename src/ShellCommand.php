<?php

namespace ArtARTs36\ShellCommand;

use ArtARTs36\ShellCommand\Concerns\HasSettings;
use ArtARTs36\ShellCommand\Concerns\HasSubCommands;
use ArtARTs36\ShellCommand\Concerns\Unshift;
use ArtARTs36\ShellCommand\Exceptions\CommandFailed;
use ArtARTs36\ShellCommand\Exceptions\ResultExceptionTrigger;
use ArtARTs36\ShellCommand\Concerns\Fluent;
use ArtARTs36\ShellCommand\Concerns\HasEnvVariables;
use ArtARTs36\ShellCommand\Concerns\HasFlows;
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
    use HasSubCommands;

    public const NAVIGATE_TO_DIR = 'cd';

    private $bin;

    private $inBackground = false;

    private $exceptions;

    private $results = [];

    public function __construct(string $bin)
    {
        $this->bin = $bin;
    }

    /**
     * Выполнить программу
     */
    public function execute(ShellCommandExecutor $executor): CommandResult
    {
        $this->results[] = $result = $executor->execute($this);

        return $result;
    }

    /**
     * @throws CommandFailed
     */
    public function executeOrFail(ShellCommandExecutor $executor): CommandResult
    {
        $result = $this->execute($executor);

        $this->handleException($result);

        return $result;
    }

    public function isExecuted(): bool
    {
        return count($this->results) > 0;
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
            $cmd .= ' ' .$joined;
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

    public function toBackground(): ShellCommandInterface
    {
        $this->inBackground = true;

        return $this;
    }

    public function inBackground(): bool
    {
        return $this->inBackground;
    }

    public function getResults(): array
    {
        return $this->results;
    }

    protected function addFlowIntoCommand(string $command): string
    {
        if ($this->getOutputFlow()) {
            $command .= ' '. $this->parseFlow(FlowType::STDOUT, $this->getOutputFlow());
        }

        if ($this->getErrorFlow() !== null) {
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
