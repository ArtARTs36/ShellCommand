<?php

namespace ArtARTs36\ShellCommand\Interfaces;

use ArtARTs36\ShellCommand\Exceptions\CommandFailed;
use ArtARTs36\ShellCommand\Result\CommandResult;

interface ShellCommandInterface
{
    /**
     * @param string $dir - path to navigate to folder
     * @param string $executor - executor alias or path
     * @return ShellCommandInterface
     */
    public static function withNavigateToDir(string $dir, string $executor);

    public static function make(string $executor = ''): ShellCommandInterface;

    /**
     * Execute the shell script
     */
    public function execute(): CommandResult;

    /**
     * @return $this
     */
    public function addParameter(string $value): self;

    /**
     * @return $this
     */
    public function addAmpersands(): self;

    /**
     * @param array<string> $values
     * @return $this
     */
    public function addParameters(array $values): self;

    /**
     * @return $this
     */
    public function addOption(string $option): self;

    /**
     * @return $this
     */
    public function addCutOption(string $option): self;

    /**
     * @return $this
     */
    public function addCutOptionWithValue(string $option, string $value): self;

    /**
     * @param string $option
     * @param string $value
     * @return $this
     */
    public function addOptionWithValue(string $option, string $value): self;

    /**
     * @return string
     */
    public function __toString(): string;

    /**
     * @param bool $condition
     * @param \Closure $value
     * @return $this
     */
    public function when(bool $condition, \Closure $value): self;

    /**
     * @param \Closure $closure
     * @param bool $and
     * @return $this
     */
    public function unshift(\Closure $closure, bool $and = false): self;

    public function inBackground(): self;

    public function setOutputFlow(string $output): ShellCommandInterface;

    public function setErrorFlow(string $error): ShellCommandInterface;

    /**
     * @return $this
     */
    public function join(ShellCommandInterface $command, bool $selfScope = false): ShellCommandInterface;

    /**
     * @throws CommandFailed
     */
    public function executeOrFail(): CommandResult;
}
