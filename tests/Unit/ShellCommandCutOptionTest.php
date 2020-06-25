<?php

namespace ArtARTs36\ShellCommand\Tests\Unit;

use ArtARTs36\ShellCommand\Settings\ShellCommandCutOption;
use PHPUnit\Framework\TestCase;

/**
 * Class ShellCommandCutOptionTest
 * @package ArtARTs36\ShellCommand\Tests\Unit
 */
class ShellCommandCutOptionTest extends TestCase
{
    /**
     * @covers \ArtARTs36\ShellCommand\Settings\ShellCommandCutOption
     */
    public function test(): void
    {
        $option = 'lock';
        $value = 'yes';

        //

        $instance = new ShellCommandCutOption($option);

        self::assertEquals('-'. $option, $instance->__toString());

        //

        $instance = new ShellCommandCutOption($option, $value);

        self::assertEquals('-'. $option . '='. $value, $instance->__toString());
    }
}
