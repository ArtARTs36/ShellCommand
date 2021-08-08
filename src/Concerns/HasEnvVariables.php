<?php

namespace ArtARTs36\ShellCommand\Concerns;

use ArtARTs36\ShellCommand\Interfaces\ShellCommandInterface;
use ArtARTs36\ShellCommand\Settings\EnvVariable;

trait HasEnvVariables
{
    /** @var array<string, EnvVariable> */
    private $env = [];

    public function addEnv(string $key, string $value): ShellCommandInterface
    {
        $this->env[$key] = new EnvVariable($key, $value);

        return $this;
    }

    protected function buildEnvLineParts(): array
    {
        $parts = [];

        if (count($this->env) > 0) {
            $parts[] = "export";
            array_push($parts, ...array_values(array_map('strval', $this->env)));
            $parts[] = '&&';
        }

        return $parts;
    }
}
