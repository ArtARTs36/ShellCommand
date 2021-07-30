<?php

namespace ArtARTs36\ShellCommand\Tests\Unit;

use ArtARTs36\ShellCommand\Settings\ShellCommandParameter;
use PHPUnit\Framework\TestCase;

class ShellCommandParameterTest extends TestCase
{
    /**
     * @covers \ArtARTs36\ShellCommand\Settings\ShellCommandParameter
     */
    public function test(): void
    {
        $parameter = 'lock';

        $instance = new ShellCommandParameter($parameter);

        self::assertEquals("'$parameter'", $instance->__toString());
    }

    /**
     * @covers \ArtARTs36\ShellCommand\Settings\ShellCommandParameter::is
     */
    public function testIs(): void
    {
        self::assertTrue(ShellCommandParameter::is('artisan'));
        self::assertFalse(ShellCommandParameter::is('-key'));
        self::assertFalse(ShellCommandParameter::is('--key'));
    }

    /**
     * @covers \ArtARTs36\ShellCommand\Settings\ShellCommandParameter::ampersands
     */
    public function testAmpersands(): void
    {
        self::assertEquals('&&', ShellCommandParameter::ampersands());
    }
}
