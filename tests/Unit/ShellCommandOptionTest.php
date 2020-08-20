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

    /**
     * @covers \ArtARTs36\ShellCommand\Settings\ShellCommandOption
     */
    public function testIs(): void
    {
        self::assertFalse(ShellCommandOption::is('&&'));
        self::assertFalse(ShellCommandOption::is('cd'));

        self::assertTrue(ShellCommandOption::is('--key'));
        self::assertTrue(ShellCommandOption::is('--key=value'));

        self::assertFalse(ShellCommandOption::is('-key'));
        self::assertFalse(ShellCommandOption::is('-key=value'));
    }

    /**
     * @covers \ArtARTs36\ShellCommand\Settings\ShellCommandOption::isWithValue
     */
    public function testIsWithValue(): void
    {
        self::assertFalse(ShellCommandOption::isWithValue('&&'));
        self::assertFalse(ShellCommandOption::isWithValue('cd'));

        self::assertFalse(ShellCommandOption::isWithValue('--key'));
        self::assertTrue(ShellCommandOption::isWithValue('--key=value'));

        self::assertFalse(ShellCommandOption::isWithValue('-key'));
        self::assertFalse(ShellCommandOption::isWithValue('-key=value'));
    }

    /**
     * @covers \ArtARTs36\ShellCommand\Settings\ShellCommandOption::explodeAttributesFromRaw
     */
    public function testExplodeAttributesFromRaw(): void
    {
        $raw = '--option';

        self::assertEquals('option', ShellCommandOption::explodeAttributesFromRaw($raw)[0]);

        //

        $raw = '--option=value';

        $expected = [
            'option',
            'value',
        ];

        self::assertEquals($expected, ShellCommandOption::explodeAttributesFromRaw($raw));
    }
}
