<?php

namespace ArtARTs36\ShellCommand\Tests\Unit;

use ArtARTs36\ShellCommand\Settings\CutOption;
use PHPUnit\Framework\TestCase;

class CutOptionTest extends TestCase
{
    public function providerForTestToString(): array
    {
        return [
            [
                '-lock', // expected
                'lock', // option
                null, // value
            ],
            [
                '-lock=yes',
                'lock',
                'yes',
            ],
        ];
    }

    /**
     * @dataProvider providerForTestToString
     * @covers \ArtARTs36\ShellCommand\Settings\CutOption::__toString
     */
    public function testToString(string $expected, string $option, ?string $value): void
    {
        self::assertEquals($expected, new CutOption($option, $value));
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
