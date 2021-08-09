<?php

namespace ArtARTs36\ShellCommand\Tests\Unit;

use ArtARTs36\ShellCommand\ShellCommander;
use PHPUnit\Framework\TestCase;

class ShellCommanderTest extends TestCase
{
    /**
     * @covers \ArtARTs36\ShellCommand\ShellCommander::makeNavigateToDir
     */
    public function testMakeNavigateToDir(): void
    {
        $commander = new ShellCommander();

        $dir = __DIR__;
        $executor = 'git';

        $command = $commander->makeNavigateToDir($dir, $executor);

        self::assertEquals("cd '{$dir}' && '$executor'", $command->__toString());
    }
}
