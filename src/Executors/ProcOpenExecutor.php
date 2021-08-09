<?php

namespace ArtARTs36\ShellCommand\Executors;

use ArtARTs36\ShellCommand\FlowType;
use ArtARTs36\ShellCommand\Interfaces\ShellCommandExecutor;
use ArtARTs36\ShellCommand\Interfaces\ShellCommandInterface;
use ArtARTs36\ShellCommand\Result\CommandResult;
use ArtARTs36\Str\Str;

class ProcOpenExecutor implements ShellCommandExecutor
{
    public function execute(ShellCommandInterface $command): CommandResult
    {
        [$process, $pipes] = $this->buildProcessAndPipes($command);

        $stdout = $this->readStream($pipes[FlowType::STDOUT]);
        $stderr = $this->readStream($pipes[FlowType::STDERR]);

        $status = proc_get_status($process);

        proc_close($process);

        return new CommandResult(
            $command,
            Str::make($stdout ?? ''),
            new \DateTime(),
            Str::make($stderr ?? ''),
            $status['exitcode']
        );
    }

    protected function buildProcessAndPipes(ShellCommandInterface $command): array
    {
        $descriptors = [
            FlowType::STDIN  => ["pipe", "r"],
            FlowType::STDOUT => ["pipe", "w"],
            FlowType::STDERR => ["pipe", "w"],
        ];

        $process = proc_open($command, $descriptors, $pipes);

        return [$process, $pipes];
    }

    protected function readStream($stream): ?string
    {
        $content = stream_get_contents($stream);

        fclose($stream);

        return $content === false ? null : $content;
    }
}
