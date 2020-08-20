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
     * @covers \ArtARTs36\ShellCommand\ShellCommand::isExecuted
     * @covers \ArtARTs36\ShellCommand\ShellCommand::__toString
     */
    public function testCreateInstance(): void
    {
        $instance = new ShellCommand('cd');

        self::assertFalse($instance->isExecuted());
        self::assertEmpty($instance->__toString());
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
     * @covers \ArtARTs36\ShellCommand\ShellCommand::addParameter
     * @covers \ArtARTs36\ShellCommand\ShellCommand::addOption
     * @covers \ArtARTs36\ShellCommand\ShellCommand::addCutOption
     */
    public function testAdds(): void
    {
        $executor = 'cp';
        $parameter = 'qwerty';

        $command = (new ShellCommand($executor, false))
            ->addParameter($parameter);

        $response = $command->__toString();

        self::assertEquals("{$executor} {$parameter} 2>&1", $response);

        //

        $option = 'r';

        $command->addOption('r');

        self::assertEquals("{$executor} {$parameter} --{$option} 2>&1", $command->__toString());

        //

        $cutOption = 'f';

        $command->addCutOption($cutOption);

        self::assertEquals("{$executor} {$parameter} --{$option} -{$cutOption} 2>&1", $command->__toString());

        //

        $value = 'ff';

        $command->addCutOptionWithValue($cutOption, $value);

        self::assertEquals(
            "{$executor} {$parameter} --{$option} -{$cutOption} -{$cutOption}={$value} 2>&1",
            $command->__toString()
        );
    }

    /**
     * @covers \ArtARTs36\ShellCommand\ShellCommand::addParameters
     */
    public function testAddParameters(): void
    {
        $executor = 'cp';

        $parameters = [
            'r',
            'f',
        ];

        $expected = implode(' ', array_merge([$executor], $parameters, ['2>&1']));

        $command = (new ShellCommand($executor, false))
            ->addParameters($parameters);

        self::assertEquals($expected, $command->__toString());
    }

    /**
     * @covers \ArtARTs36\ShellCommand\ShellCommand::addOptionWithValue
     */
    public function testAddOptionWithValue(): void
    {
        $option = 'key';
        $value = 'fLdefmEkvcdsmsefeskeEfLfde';
        $executor = 'test-api';

        $command = (new ShellCommand($executor, false))
            ->addOptionWithValue($option, $value);

        self::assertEquals("{$executor} --{$option}={$value} 2>&1", $command->__toString());
    }

    /**
     * @covers \ArtARTs36\ShellCommand\ShellCommand::getInstanceWithMoveDir
     */
    public function testGetInstanceWithMoveDir(): void
    {
        $dir = __DIR__;
        $executor = 'git';

        $command = ShellCommand::getInstanceWithMoveDir($dir, $executor);

        self::assertEquals("cd {$dir} && $executor 2>&1", $command->__toString());
    }

    /**
     * @covers \ArtARTs36\ShellCommand\ShellCommand::when
     */
    public function testWhen(): void
    {
        $command = (new ShellCommand('git', false))
            ->when(false, function (ShellCommand $command) {
                $command->addParameter('pull');
            });

        self::assertEquals('git 2>&1', $command->__toString());

        //

        $command->when(true, function (ShellCommand $command) {
            $command->addParameter('pull');
        });

        self::assertEquals('git pull 2>&1', $command->__toString());
    }

    /**
     * @return ShellCommand
     */
    protected function makeCommand(): ShellCommand
    {
        return new ShellCommand('', false);
    }
}
