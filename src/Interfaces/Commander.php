<?php

namespace ArtARTs36\ShellCommand\Interfaces;

interface Commander extends ShellCommandExecutor, CommandBuilder
{
    /**
     * @return $this
     */
    public function useExecutor(ShellCommandExecutor $executor);

    public function fromRaw(string $command): ShellCommandInterface;
}
