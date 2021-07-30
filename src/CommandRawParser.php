<?php

namespace ArtARTs36\ShellCommand;

use ArtARTs36\ShellCommand\Settings\CutOption;
use ArtARTs36\ShellCommand\Settings\Join;
use ArtARTs36\ShellCommand\Settings\Option;
use ArtARTs36\ShellCommand\Settings\Argument;

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
            if (Join::is($param)) {
                $command->addAmpersands();
            } elseif (Argument::is($param)) {
                $command->addArgument($param);
            } elseif (Option::isWithValue($param)) {
                $command->addOptionWithValue(...Option::explodeAttributesFromRaw($param));
            } elseif (Option::is($param)) {
                $command->addOption($param);
            } elseif (CutOption::isWithValue($param)) {
                $command->addCutOptionWithValue(...CutOption::explodeAttributesFromRaw($param));
            } elseif (CutOption::is($param)) {
                $command->addCutOption($param);
            }
        }

        return $command;
    }
}
