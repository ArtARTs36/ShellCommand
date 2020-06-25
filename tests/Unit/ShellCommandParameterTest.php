<?php

namespace ArtARTs36\ShellCommand\Tests\Unit;

use ArtARTs36\ShellCommand\Settings\ShellCommandParameter;
use PHPUnit\Framework\TestCase;

/**
 * Class ShellCommandParameterTest
 * @package ArtARTs36\ShellCommand\Tests\Unit
 */
class ShellCommandParameterTest extends TestCase
{
    /**
     * @covers \ArtARTs36\ShellCommand\Settings\ShellCommandParameter
     */
    public function test(): void
    {
        $parameter = 'lock';

        $instance = new ShellCommandParameter($parameter);

        self::assertEquals($parameter, $instance->__toString());

        //

        $instance = new ShellCommandParameter($parameter, true);

        self::assertEquals('"'. $parameter . '"', $instance->__toString());
    }
}
