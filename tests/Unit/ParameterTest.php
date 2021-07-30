<?php

namespace ArtARTs36\ShellCommand\Tests\Unit;

use ArtARTs36\ShellCommand\Settings\Argument;
use PHPUnit\Framework\TestCase;

class ParameterTest extends TestCase
{
    /**
     * @covers \ArtARTs36\ShellCommand\Settings\Argument
     */
    public function test(): void
    {
        $parameter = 'lock';

        $instance = new Argument($parameter);

        self::assertEquals("'$parameter'", $instance->__toString());
    }

    /**
     * @covers \ArtARTs36\ShellCommand\Settings\Argument::is
     */
    public function testIs(): void
    {
        self::assertTrue(Argument::is('artisan'));
        self::assertFalse(Argument::is('-key'));
        self::assertFalse(Argument::is('--key'));
    }
}
