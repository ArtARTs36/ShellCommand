<?php

namespace ArtARTs36\ShellCommand\Tests\Unit;

use ArtARTs36\ShellCommand\Settings\Option;
use PHPUnit\Framework\TestCase;

/**
 * Class ShellCommandOptionTest
 * @package ArtARTs36\ShellCommand\Tests\Unit
 */
class OptionTest extends TestCase
{
    /**
     * @covers \ArtARTs36\ShellCommand\Settings\Option
     */
    public function test(): void
    {
        $option = 'lock';

        $instance = new Option($option);

        self::assertEquals('--'. $option, $instance->__toString());

        //

        $value = 'yes';

        $instance = new Option($option, $value);

        self::assertEquals('--'. $option . '='. $value, $instance->__toString());
    }

    /**
     * @covers \ArtARTs36\ShellCommand\Settings\Option
     */
    public function testIs(): void
    {
        self::assertFalse(Option::is('&&'));
        self::assertFalse(Option::is('cd'));

        self::assertTrue(Option::is('--key'));
        self::assertTrue(Option::is('--key=value'));

        self::assertFalse(Option::is('-key'));
        self::assertFalse(Option::is('-key=value'));
    }

    /**
     * @covers \ArtARTs36\ShellCommand\Settings\Option::isWithValue
     */
    public function testIsWithValue(): void
    {
        self::assertFalse(Option::isWithValue('&&'));
        self::assertFalse(Option::isWithValue('cd'));

        self::assertFalse(Option::isWithValue('--key'));
        self::assertTrue(Option::isWithValue('--key=value'));

        self::assertFalse(Option::isWithValue('-key'));
        self::assertFalse(Option::isWithValue('-key=value'));
    }

    /**
     * @covers \ArtARTs36\ShellCommand\Settings\Option::explodeAttributesFromRaw
     */
    public function testExplodeAttributesFromRaw(): void
    {
        $raw = '--option';

        self::assertEquals('option', Option::explodeAttributesFromRaw($raw)[0]);

        //

        $raw = '--option=value';

        $expected = [
            'option',
            'value',
        ];

        self::assertEquals($expected, Option::explodeAttributesFromRaw($raw));
    }
}
