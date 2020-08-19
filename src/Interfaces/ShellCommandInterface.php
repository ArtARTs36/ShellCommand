<?php

namespace ArtARTs36\ShellCommand\Interfaces;

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

    /**
     * @return $this
     */
    public function execute(): self;

    /**
     * @param $value
     * @return $this
     */
    public function addParameter($value): self;

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
     * @param $option
     * @return $this
     */
    public function addOption($option): self;

    /**
     * @param $option
     * @return $this
     */
    public function addCutOption($option): self;

    /**
     * @param string $option
     * @param string $value
     * @return $this
     */
    public function addOptionWithValue(string $option, string $value): self;

    /**
     * @return mixed
     */
    public function getShellResult();

    /**
     * @return bool
     */
    public function isExecuted(): bool;

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
}
