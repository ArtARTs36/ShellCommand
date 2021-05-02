<?php

namespace ArtARTs36\ShellCommand\Tests\Unit;

use ArtARTs36\ShellCommand\FlowType;
use PHPUnit\Framework\TestCase;

class FlowTypeTest extends TestCase
{
    /**
     * @covers \ArtARTs36\ShellCommand\FlowType::is
     */
    public function testIs(): void
    {
        self::assertTrue(FlowType::is(FlowType::STDIN));
        self::assertTrue(FlowType::is(FlowType::STDOUT));
        self::assertTrue(FlowType::is(FlowType::STDERR));
    }
}
