<?php

namespace ArtARTs36\ShellCommand;

use ArtARTs36\ShellCommand\Settings\ShellCommandCutOption;
use ArtARTs36\ShellCommand\Settings\ShellCommandOption;
use ArtARTs36\ShellCommand\Settings\ShellCommandParameter;

/**
 * Class CommandRawParser
 * @package ArtARTs36\ShellCommand
 */
final class CommandRawParser
{
    /** @var string */
    private $raw;

    /**
     * CommandRawParser constructor.
     * @param string $raw
     */
    public function __construct(string $raw)
    {
        $this->raw = $raw;
    }

    /**
     * @param string $raw
     * @return ShellCommand
     */
    public static function parse(string $raw): ShellCommand
    {
        return (new static($raw))->createCommand();
    }

    /**
     * @return ShellCommand
     */
    public function createCommand(): ShellCommand
    {
        $params = explode(' ', $this->raw);

        $command = new ShellCommand('', false);

        foreach ($params as $param) {
            if (ShellCommandParameter::is($param)) {
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
