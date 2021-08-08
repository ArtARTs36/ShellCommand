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

        $command = (new ShellCommand($executor))
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

        $command = (new ShellCommand($executor))
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

        $command = (new ShellCommand($executor))
            ->addOptionWithValue($option, $value);

        self::assertEquals("{$executor} --{$option}={$value} 2>&1", $command->__toString());
    }

    /**
     * @covers \ArtARTs36\ShellCommand\ShellCommand::getInstanceWithMoveDir
     * @todo Will Be Removed
     */
    public function testGetInstanceWithMoveDir(): void
    {
        $this->expectDeprecation();

        $dir = __DIR__;
        $executor = 'git';

        $command = ShellCommand::getInstanceWithMoveDir($dir, $executor);

        self::assertEquals("cd {$dir} && $executor 2>&1", $command->__toString());
    }

    /**
     * @covers \ArtARTs36\ShellCommand\ShellCommand::withNavigateToDir
     */
    public function testWithNavigateToDir(): void
    {
        $dir = __DIR__;
        $executor = 'git';

        $command = ShellCommand::withNavigateToDir($dir, $executor);

        self::assertEquals("cd {$dir} && $executor 2>&1", $command->__toString());
    }

    /**
     * @covers \ArtARTs36\ShellCommand\ShellCommand::when
     */
    public function testWhen(): void
    {
        $command = (new ShellCommand('git'))
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
     * @covers \ArtARTs36\ShellCommand\ShellCommand::unshift
     */
    public function testUnshift(): void
    {
        $cmd = $this->makeCommand()
            ->addParameter('git')
            ->addParameter('pull');

        self::assertEquals('git pull 2>&1', $cmd->__toString());

        //

        $cmd->unshift(function (ShellCommand $command) {
            $command
                ->addParameter('cd')
                ->addParameter('/var/web')
                ->addAmpersands();
        });

        self::assertEquals('cd /var/web && git pull 2>&1', $cmd->__toString());

        //

        $cmd = $this->makeCommand();

        $cmd->unshift(function (ShellCommand $command) {
            $command
                ->addParameter('less')
                ->addParameter('.env');
        }, true);

        self::assertEquals('less .env 2>&1', $cmd->__toString());

        $cmd->unshift(function (ShellCommand $command) {
            $command
                ->addParameter('cd')
                ->addParameter('/var/');
        }, true);

        self::assertEquals('cd /var/ && less .env 2>&1', $cmd->__toString());
    }

    /**
     * @covers \ArtARTs36\ShellCommand\ShellCommand::inBackground
     */
    public function testInBackground(): void
    {
        $cmd = new ShellCommand('pg_dump');
        $cmd->addParameter('database');
        $cmd->setOutputFlow('dump.sql');
        $cmd->inBackground();

        self::assertEquals('pg_dump database 1>dump.sql 2>/dev/null &', $cmd->__toString());
    }

    /**
     * @covers \ArtARTs36\ShellCommand\ShellCommand::addEnv
     */
    public function testAddEnv(): void
    {
        $cmd = (new ShellCommand('echo'))
            ->addEnv('NAME', 'Artem')
            ->addEnv('FAMILY', 'Ukrainskiy')
            ->addParameter('$NAME')
            ->addAmpersands()
            ->addParameter('echo')
            ->addParameter('$FAMILY');

        self::assertEquals('export NAME=Artem FAMILY=Ukrainskiy && echo $NAME && echo $FAMILY 2>&1', $cmd);
    }

    /**
     * @covers \ArtARTs36\ShellCommand\ShellCommand::addPipe
     */
    public function testAddPipe(): void
    {
        $cmd = ShellCommand::make('cat')
            ->addParameter('file.txt')
            ->addPipe()
            ->addParameter('gzip')
            ->addCutOption('c');

        self::assertEquals('cat file.txt | gzip -c 2>&1', $cmd);
    }

    /**
     * @return ShellCommand
     */
    protected function makeCommand(): ShellCommand
    {
        return new ShellCommand('');
    }
}
