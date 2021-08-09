<?php

namespace ArtARTs36\ShellCommand\Interfaces;

use ArtARTs36\ShellCommand\Exceptions\CommandFailed;
use ArtARTs36\ShellCommand\Result\CommandResult;

interface ShellCommandInterface
{
    public function addEnv(string $key, string $value): ShellCommandInterface;

    /**
     * Execute the command
     */
    public function execute(ShellCommandExecutor $executor): CommandResult;

    /**
     * Execute the command or failure Exception
     * @throws CommandFailed
     */
    public function executeOrFail(ShellCommandExecutor $executor): CommandResult;

    public function isExecuted(): bool;

    public function addArgument(string $value, bool $escape = true): self;

    /**
     * @return $this
     */
    public function addAmpersands(): self;

    /**
     * @param array<string> $values
     * @return $this
     */
    public function addArguments(array $values): self;

    /**
     * Add option into command line
     */
    public function addOption(string $option): self;

    /**
     * @return $this
     */
    public function addCutOption(string $option): self;

    /**
     * Add cut option with value into command line
     * @return $this
     */
    public function addCutOptionWithValue(string $option, string $value): self;

    /**
     * Add option with value into command line
     */
    public function addOptionWithValue(string $option, string $value): self;

    /**
     * @return string
     */
    public function __toString(): string;

    /**
     * Call $value if $condition === true
     */
    public function when(bool $condition, \Closure $value): self;

    /**
     * @param \Closure $closure
     * @param bool $and
     * @return $this
     */
    public function unshift(\Closure $closure, bool $and = false): self;

    public function toBackground(): self;

    public function inBackground(): bool;

    public function setOutputFlow(string $output): ShellCommandInterface;

    /**
     * Add pipe "|" into command line
     */
    public function addPipe(): ShellCommandInterface;

    public function setErrorFlow(string $error): ShellCommandInterface;

    /**
     * @return $this
     */
    public function joinAnd(ShellCommandInterface $command, bool $selfScope = false): ShellCommandInterface;

    /**
     * @return $this
     */
    public function joinAnyway(ShellCommandInterface $command, bool $selfScope = false): ShellCommandInterface;

    public function joinOr(ShellCommandInterface $command, bool $selfScope = false): ShellCommandInterface;

    /**
     * @return array<ShellSettingInterface>
     */
    public function getSettings(): array;

    /**
     * @return array<CommandResult>
     */
    public function getResults(): array;
}
