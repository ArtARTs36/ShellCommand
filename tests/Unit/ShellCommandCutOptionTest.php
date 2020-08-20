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

    /**
     * @covers \ArtARTs36\ShellCommand\Settings\ShellCommandCutOption::is
     */
    public function testIs(): void
    {
        self::assertTrue(ShellCommandCutOption::is('-key'));
        self::assertTrue(ShellCommandCutOption::is('-key=value'));

        self::assertFalse(ShellCommandCutOption::is('--key'));
        self::assertFalse(ShellCommandCutOption::is('--key=value'));
    }

    /**
     * @covers \ArtARTs36\ShellCommand\Settings\ShellCommandOption::isWithValue
     */
    public function testIsWithValue(): void
    {
        self::assertFalse(ShellCommandCutOption::isWithValue('&&'));
        self::assertFalse(ShellCommandCutOption::isWithValue('cd'));

        self::assertFalse(ShellCommandCutOption::isWithValue('-key'));
        self::assertTrue(ShellCommandCutOption::isWithValue('-key=value'));

        self::assertFalse(ShellCommandCutOption::isWithValue('--key'));
        self::assertFalse(ShellCommandCutOption::isWithValue('--key=value'));
    }

    /**
     * @covers \ArtARTs36\ShellCommand\Settings\ShellCommandOption::explodeAttributesFromRaw
     */
    public function testExplodeAttributesFromRaw(): void
    {
        $raw = '-option';

        self::assertEquals('option', ShellCommandCutOption::explodeAttributesFromRaw($raw)[0]);

        //

        $raw = '-option=value';

        $expected = [
            'option',
            'value',
        ];

        self::assertEquals($expected, ShellCommandCutOption::explodeAttributesFromRaw($raw));
    }
}
