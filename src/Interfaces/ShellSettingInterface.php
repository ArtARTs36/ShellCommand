<?php

namespace ArtARTs36\ShellCommand\Interfaces;

/**
 * In Php 8: extends \Stringable
 */
interface ShellSettingInterface
{
    public function __toString(): string;
}
