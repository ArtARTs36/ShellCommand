<?php

namespace ArtARTs36\ShellCommand\Tests\Unit;

use ArtARTs36\ShellCommand\Executors\TestExecutor;
use ArtARTs36\ShellCommand\ShellCommand;
use PHPUnit\Framework\TestCase;

class TestExecutorTest extends TestCase
{
    /**
     * @covers \ArtARTs36\ShellCommand\Executors\TestExecutor::execute
     */
    public function testExecuteOnUnexpectedCommand(): void
    {
        $executor = new TestExecutor();

        self::expectExceptionMessage('Unexpected command: git');

        $executor->execute((new ShellCommand('git')));
    }
}
