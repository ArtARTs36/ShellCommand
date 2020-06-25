<?php

namespace ArtARTs36\ShellCommand\Tests\Unit;

use ArtARTs36\ShellCommand\ShellCommand;
use PHPUnit\Framework\TestCase;

/**
 * Class ShellCommandTest
 * @package ArtARTs36\ShellCommand\Tests\Unit
 */
class ShellCommandTest extends TestCase
{
    /**
     * @covers \ArtARTs36\ShellCommand\ShellCommand::__construct
     * @covers \ArtARTs36\ShellCommand\ShellCommand::getInstance
     * @covers \ArtARTs36\ShellCommand\ShellCommand::isExecuted
     * @covers \ArtARTs36\ShellCommand\ShellCommand::__toString
     */
    public function testCreateInstance(): void
    {
        $instances = [
            new ShellCommand('cd'),
            ShellCommand::getInstance('cd')
        ];

        foreach ($instances as $instance) {
            self::assertFalse($instance->isExecuted());
            self::assertEmpty($instance->__toString());
        }
    }

    /**
     * @covers \ArtARTs36\ShellCommand\ShellCommand::addAmpersands
     */
    public function testAddAmpersands(): void
    {
        $command = $this->makeCommand()
            ->addAmpersands();

        self::assertStringContainsString('&&', $command->__toString());
    }

    /**
     * @return ShellCommand
     */
    protected function makeCommand(): ShellCommand
    {
        return new ShellCommand('');
    }
}
