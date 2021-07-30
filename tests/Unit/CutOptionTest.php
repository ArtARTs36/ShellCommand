<?php

namespace ArtARTs36\ShellCommand\Tests\Unit;

use ArtARTs36\ShellCommand\Settings\CutOption;
use PHPUnit\Framework\TestCase;

/**
 * Class ShellCommandCutOptionTest
 * @package ArtARTs36\ShellCommand\Tests\Unit
 */
class CutOptionTest extends TestCase
{
    /**
     * @covers \ArtARTs36\ShellCommand\Settings\CutOption
     */
    public function test(): void
    {
        $option = 'lock';
        $value = 'yes';

        //

        $instance = new CutOption($option);

        self::assertEquals('-'. $option, $instance->__toString());

        //

        $instance = new CutOption($option, $value);

        self::assertEquals('-'. $option . '='. $value, $instance->__toString());
    }

    /**
     * @covers \ArtARTs36\ShellCommand\Settings\CutOption::is
     */
    public function testIs(): void
    {
        self::assertTrue(CutOption::is('-key'));
        self::assertTrue(CutOption::is('-key=value'));

        self::assertFalse(CutOption::is('--key'));
        self::assertFalse(CutOption::is('--key=value'));
    }

    /**
     * @covers \ArtARTs36\ShellCommand\Settings\Option::isWithValue
     */
    public function testIsWithValue(): void
    {
        self::assertFalse(CutOption::isWithValue('&&'));
        self::assertFalse(CutOption::isWithValue('cd'));

        self::assertFalse(CutOption::isWithValue('-key'));
        self::assertTrue(CutOption::isWithValue('-key=value'));

        self::assertFalse(CutOption::isWithValue('--key'));
        self::assertFalse(CutOption::isWithValue('--key=value'));
    }

    /**
     * @covers \ArtARTs36\ShellCommand\Settings\Option::explodeAttributesFromRaw
     */
    public function testExplodeAttributesFromRaw(): void
    {
        $raw = '-option';

        self::assertEquals('option', CutOption::explodeAttributesFromRaw($raw)[0]);

        //

        $raw = '-option=value';

        $expected = [
            'option',
            'value',
        ];

        self::assertEquals($expected, CutOption::explodeAttributesFromRaw($raw));
    }
}
