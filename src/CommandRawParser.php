<?php

namespace ArtARTs36\ShellCommand;

use ArtARTs36\ShellCommand\Settings\ShellCommandCutOption;
use ArtARTs36\ShellCommand\Settings\ShellCommandJoin;
use ArtARTs36\ShellCommand\Settings\ShellCommandOption;
use ArtARTs36\ShellCommand\Settings\ShellCommandParameter;

final class CommandRawParser
{
    public static function parse(string $raw): ShellCommand
    {
        return (new self())->createCommand($raw);
    }

    public function createCommand(string $raw): ShellCommand
    {
        $params = explode(' ', $raw);

        $command = ShellCommand::make();

        foreach ($params as $param) {
            if (ShellCommandJoin::is($param)) {
                $command->addAmpersands();
            } elseif (ShellCommandParameter::is($param)) {
                $command->addParameter($param);
            } elseif (ShellCommandOption::isWithValue($param)) {
                $command->addOptionWithValue(...ShellCommandOption::explodeAttributesFromRaw($param));
            } elseif (ShellCommandOption::is($param)) {
                $command->addOption($param);
            } elseif (ShellCommandCutOption::isWithValue($param)) {
                $command->addCutOptionWithValue(...ShellCommandCutOption::explodeAttributesFromRaw($param));
            } elseif (ShellCommandCutOption::is($param)) {
                $command->addCutOption($param);
            }
        }

        return $command;
    }
}
