<?php

namespace ArtARTs36\ShellCommand\Concerns;

use ArtARTs36\ShellCommand\FlowType;
use ArtARTs36\ShellCommand\Interfaces\ShellCommandInterface;

trait HasFlows
{
    private $outputFlow;

    private $errorFlow = null;

    public function getErrorFlow(): ?string
    {
        return $this->errorFlow;
    }

    public function getOutputFlow(): ?string
    {
        return $this->outputFlow;
    }

    public function setOutputFlow(string $output): ShellCommandInterface
    {
        $this->outputFlow = $output;

        return $this;
    }

    public function setErrorFlow(string $error): ShellCommandInterface
    {
        $this->errorFlow = $error;

        return $this;
    }

    protected function parseFlow(int $type, string $value): string
    {
        return $type . '>'. $value;
    }
}
