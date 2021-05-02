<?php

namespace ArtARTs36\ShellCommand\Tests\Unit;

use ArtARTs36\ShellCommand\FlowType;
use ArtARTs36\ShellCommand\Settings\FlowRedirect;
use PHPUnit\Framework\TestCase;

class FlowRedirectTest extends TestCase
{
    /**
     * @covers \ArtARTs36\ShellCommand\Settings\FlowRedirect::__toString
     */
    public function test(): void
    {
        $redirect = new FlowRedirect(FlowType::STDERR, '/dev/null');

        self::assertEquals('2 > /dev/null', $redirect->__toString());
    }
}
