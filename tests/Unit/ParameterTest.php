<?php

namespace ArtARTs36\ShellCommand\Tests\Unit;

use ArtARTs36\ShellCommand\Settings\Parameter;
use PHPUnit\Framework\TestCase;

class ParameterTest extends TestCase
{
    /**
     * @covers \ArtARTs36\ShellCommand\Settings\Parameter
     */
    public function test(): void
    {
        $parameter = 'lock';

        $instance = new Parameter($parameter);

        self::assertEquals("'$parameter'", $instance->__toString());
    }

    /**
     * @covers \ArtARTs36\ShellCommand\Settings\Parameter::is
     */
    public function testIs(): void
    {
        self::assertTrue(Parameter::is('artisan'));
        self::assertFalse(Parameter::is('-key'));
        self::assertFalse(Parameter::is('--key'));
    }
}
