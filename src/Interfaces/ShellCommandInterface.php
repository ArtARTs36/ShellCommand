<?php

namespace ArtARTs36\ShellCommand\Interfaces;

use ArtARTs36\ShellCommand\Result\CommandResult;

/**
 * Interface ShellCommandInterface
 * @package ArtARTs36\ShellCommand\Interfaces
 */
interface ShellCommandInterface
{
    /**
     * @param string $dir
     * @param string $executor
     * @return ShellCommandInterface
     */
    public static function getInstanceWithMoveDir(string $dir, string $executor);

    public function execute(): CommandResult;

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
     * @param array<string> $values
     * @return $this
     */
    public function addParameters(array $values): self;

    /**
     * @return $this
     */
    public function addOption(string $option): self;

    /**
     * @param $option
     * @return $this
     */
    public function addCutOption($option): self;

    /**
     * @param string $option
     * @param mixed $value
     * @return $this
     */
    public function addCutOptionWithValue($option, $value): self;

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
}
