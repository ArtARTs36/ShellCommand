<?php

namespace ArtARTs36\ShellCommand\Concerns;

use ArtARTs36\ShellCommand\FlowType;
use ArtARTs36\ShellCommand\Interfaces\ShellCommandInterface;

trait HasFlows
{
    private $outputFlow;

    private $errorFlow = null;

    /**
     * @return false|string
     */
    public function getErrorFlow()
    {
        if ($this->errorFlow === false) {
            return false;
        }

        if ($this->inBackground && ! $this->errorFlow) {
            return '/dev/null';
        }

        return $this->errorFlow ?? '&'. FlowType::STDOUT;
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

    public function setErrorFlow($error): ShellCommandInterface
    {
        $this->errorFlow = $error;

        return $this;
    }

    protected function parseFlow(int $type, string $value): string
    {
        return $type . '>'. $value;
    }
}
