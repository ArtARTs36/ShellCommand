<?php

namespace ArtARTs36\ShellCommand\Interfaces;

interface CommandBuilder
{
    /**
     * @param string $dir - path to navigate to folder
     * @param string $bin - executor alias or path
     */
    public function makeNavigateToDir(string $dir, string $bin): ShellCommandInterface;

    public function make(string $bin = ''): ShellCommandInterface;
}
