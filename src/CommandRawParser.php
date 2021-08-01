<?php

namespace ArtARTs36\ShellCommand;

use ArtARTs36\ShellCommand\Settings\CutOption;
use ArtARTs36\ShellCommand\Settings\Join;
use ArtARTs36\ShellCommand\Settings\Option;
use ArtARTs36\ShellCommand\Settings\Argument;

class CommandRawParser
{
    /** @link https://regexlib.com/REDetails.aspx?regexp_id=13053 */
    protected $regex = '/(?:-+([^= \'\"]+)[= ]?)?(?:([\'\"])([^\2]+?)\2|([^- \"\']+))?/';

    public static function parse(string $raw): ShellCommand
    {
        return (new self())->createCommand($raw);
    }

    public function createCommand(string $raw): ShellCommand
    {
        $command = ShellCommand::make();

        $params = $this->parseRawExpression($raw);

        if ($params === null) {
            return $command;
        }

        foreach ($params as $param) {
            if (Join::is($param)) {
                $command->addAmpersands();
            } elseif (Argument::is($param)) {
                $command->addArgument($param);
            } elseif (Option::isWithValue($param)) {
                $command->addOptionWithValue(...Option::explodeAttributesFromRaw($param));
            } elseif (Option::is($param)) {
                $command->addOption(...Option::explodeAttributesFromRaw($param));
            } elseif (CutOption::isWithValue($param)) {
                $command->addCutOptionWithValue(...CutOption::explodeAttributesFromRaw($param));
            } elseif (CutOption::is($param)) {
                $command->addCutOption(...CutOption::explodeAttributesFromRaw($param));
            }
        }

        return $command;
    }

    protected function parseRawExpression(string $raw): ?array
    {
        $params = [];
        preg_match_all($this->regex, $raw, $params);

        if (! isset($params[0])) {
            return null;
        }

        return array_map('trim', array_filter($params[0]));
    }
}
