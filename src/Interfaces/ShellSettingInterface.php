<?php

namespace ArtARTs36\ShellCommand\Interfaces;

/**
 * Interface ShellSettingInterface
 * @package ArtARTs36\ShellCommand\Interfaces
 *
 * In Php 8: extends \Stringable
 */
interface ShellSettingInterface
{
    /**
     * @return string
     */
    public function __toString(): string;
}
