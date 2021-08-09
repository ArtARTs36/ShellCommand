<?php

namespace ArtARTs36\ShellCommand\Interfaces;

use ArtARTs36\ShellCommand\Result\CommandResult;

interface ShellCommandInterface
{
    /**
     * @param string $dir
     * @param string $bin
     * @return ShellCommandInterface
     * @deprecated
     */
    public static function getInstanceWithMoveDir(string $dir, string $bin);

    /**
     * @param string $dir - path to navigate to folder
     * @param string $executor - executor alias or path
     */
    public static function withNavigateToDir(string $dir, string $executor);

    public static function make(string $bin = ''): ShellCommandInterface;

    public function addEnv(string $key, string $value): ShellCommandInterface;

    public function setExecutor(ShellCommandExecutor $executor): ShellCommandInterface;

    /**
     * @return $this
     */
    public function execute(): self;

    /**
     * @param $value
     * @param bool $quotes
     * @return $this
     */
    public function addParameter($value, bool $quotes = false): self;

    /**
     * @return $this
     */
    public function addAmpersands(): self;

    /**
     * @param array $values
     * @return $this
     */
    public function addParameters(array $values): self;

    /**
     * Add option into command line
     * @param string $option
     */
    public function addOption($option): self;

    /**
     * @param $option
     * @return $this
     */
    public function addCutOption($option): self;

    /**
     * Add cut option with value into command line
     * @param string $option
     * @param string $value
     */
    public function addCutOptionWithValue($option, $value): self;

    /**
     * Add option with value into command line
     */
    public function addOptionWithValue(string $option, string $value): self;

    /**
     * @return CommandResult
     */
    public function getShellResult();

    public function isExecuted(): bool;

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

    public function inBackground(): self;

    public function setOutputFlow(string $output): ShellCommandInterface;

    /**
     * @param string|false $error
     * @return ShellCommandInterface
     */
    public function setErrorFlow($error): ShellCommandInterface;

    public function addPipe(): ShellCommandInterface;
}
