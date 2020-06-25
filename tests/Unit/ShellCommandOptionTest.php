<?php

namespace ArtARTs36\ShellCommand\Tests\Unit;

use ArtARTs36\ShellCommand\Settings\ShellCommandOption;
use PHPUnit\Framework\TestCase;

/**
 * Class ShellCommandOptionTest
 * @package ArtARTs36\ShellCommand\Tests\Unit
 */
class ShellCommandOptionTest extends TestCase
{
    /**
     * @covers \ArtARTs36\ShellCommand\Settings\ShellCommandOption
     */
    public function test(): void
    {
        $option = 'lock';

        $instance = new ShellCommandOption($option);

        self::assertEquals('--'. $option, $instance->__toString());

        //

        $value = 'yes';

        $instance = new ShellCommandOption($option, $value);

        self::assertEquals('--'. $option . '='. $value, $instance->__toString());
    }
}
