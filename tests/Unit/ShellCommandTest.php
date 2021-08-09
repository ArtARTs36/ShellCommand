<?php

namespace ArtARTs36\ShellCommand\Tests\Unit;

use ArtARTs36\ShellCommand\ShellCommand;
use PHPUnit\Framework\TestCase;

class ShellCommandTest extends TestCase
{
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
     * @covers \ArtARTs36\ShellCommand\ShellCommand::addArgument
     * @covers \ArtARTs36\ShellCommand\ShellCommand::addOption
     * @covers \ArtARTs36\ShellCommand\ShellCommand::addCutOption
     */
    public function testAdds(): void
    {
        $executor = 'cp';
        $parameter = 'qwerty';

        $command = (new ShellCommand($executor))
            ->addArgument($parameter);

        $response = $command->__toString();

        self::assertEquals("{$executor} '{$parameter}'", $response);

        //

        $option = 'r';

        $command->addOption('r');

        self::assertEquals("{$executor} '{$parameter}' --{$option}", $command->__toString());

        //

        $cutOption = 'f';

        $command->addCutOption($cutOption);

        self::assertEquals("{$executor} '{$parameter}' --{$option} -{$cutOption}", $command->__toString());

        //

        $value = 'ff';

        $command->addCutOptionWithValue($cutOption, $value);

        self::assertEquals(
            "{$executor} '{$parameter}' --{$option} -{$cutOption} -{$cutOption}={$value}",
            $command->__toString()
        );
    }

    /**
     * @covers \ArtARTs36\ShellCommand\ShellCommand::addArguments
     */
    public function testAddParameters(): void
    {
        $executor = 'cp';

        $parameters = [
            'r',
            'f',
        ];

        $expected = "cp 'r' 'f'";

        $command = (new ShellCommand($executor))
            ->addArguments($parameters);

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

        self::assertEquals("{$executor} --{$option}={$value}", $command->__toString());
    }

    /**
     * @covers \ArtARTs36\ShellCommand\ShellCommand::when
     */
    public function testWhen(): void
    {
        $command = $this->makeCommand('git')
            ->when(false, function (ShellCommand $command) {
                $command->addArgument('pull');
            });

        self::assertEquals('git', $command->__toString());

        //

        $command->when(true, function (ShellCommand $command) {
            $command->addArgument('pull');
        });

        self::assertEquals("git 'pull'", $command->__toString());
    }

    /**
     * @covers \ArtARTs36\ShellCommand\ShellCommand::unshift
     */
    public function testUnshift(): void
    {
        $cmd = $this->makeCommand()
            ->addArgument('git')
            ->addArgument('pull');

        self::assertEquals("'git' 'pull'", $cmd->__toString());

        //

        $cmd->unshift(function (ShellCommand $command) {
            $command
                ->addArgument('cd')
                ->addArgument('/var/web')
                ->addAmpersands();
        });

        self::assertEquals("'cd' '/var/web' && 'git' 'pull'", $cmd->__toString());

        //

        $cmd = $this->makeCommand();

        $cmd->unshift(function (ShellCommand $command) {
            $command
                ->addArgument('less')
                ->addArgument('.env');
        }, true);

        self::assertEquals("'less' '.env'", $cmd->__toString());

        $cmd->unshift(function (ShellCommand $command) {
            $command
                ->addArgument('cd')
                ->addArgument('/var/');
        }, true);

        self::assertEquals("'cd' '/var/' && 'less' '.env'", $cmd->__toString());
    }

    /**
     * @covers \ArtARTs36\ShellCommand\ShellCommand::toBackground
     */
    public function testToBackground(): void
    {
        $cmd = $this->makeCommand('pg_dump')
            ->addArgument('database')
            ->setOutputFlow('dump.sql')
            ->toBackground();

        self::assertEquals("pg_dump 'database' 1>dump.sql &", $cmd->__toString());
    }

    /**
     * @covers \ArtARTs36\ShellCommand\ShellCommand::addEnv
     * @covers \ArtARTs36\ShellCommand\ShellCommand::buildEnvLineParts
     */
    public function testAddEnv(): void
    {
        $cmd = $this->makeCommand('echo')
            ->addEnv('NAME', 'Artem')
            ->addEnv('FAMILY', 'Ukrainskiy')
            ->addArgument('$NAME')
            ->addAmpersands()
            ->addArgument('echo')
            ->addArgument('$FAMILY');

        self::assertEquals(
            'export NAME=Artem FAMILY=Ukrainskiy && echo \'$NAME\' && \'echo\' \'$FAMILY\'',
            (string) $cmd
        );
    }

    /**
     * @covers \ArtARTs36\ShellCommand\ShellCommand::addPipe
     */
    public function testAddPipe(): void
    {
        $cmd = $this->makeCommand('cat')
            ->addArgument('file.txt')
            ->addPipe()
            ->addArgument('gzip')
            ->addCutOption('c');

        self::assertEquals('cat \'file.txt\' | \'gzip\' -c', (string) $cmd);
    }

    /**
     * @return ShellCommand
     */
    protected function makeCommand(string $bin = ''): ShellCommand
    {
        return new ShellCommand($bin);
    }
}
