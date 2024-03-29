<?php

namespace ArtARTs36\ShellCommand\Executors;

use ArtARTs36\ShellCommand\Interfaces\ShellCommandExecutor;
use ArtARTs36\ShellCommand\Interfaces\ShellCommandInterface;
use ArtARTs36\ShellCommand\Result\CommandResult;
use ArtARTs36\ShellCommand\Result\ResultCode;
use ArtARTs36\Str\Str;
use PHPUnit\Framework\Assert;

class TestExecutor implements ShellCommandExecutor
{
    protected $results = [];

    protected $attempts = 0;

    public static function fromFail(
        string $stdErr = 'Fail',
        string $stdout = '',
        int $code = ResultCode::GENERAL_ERRORS
    ): self {
        $instance = new self();
        $instance->addFail(...func_get_args());

        return $instance;
    }

    public static function fromSuccess(string $stdout = 'Success', string $stdErr = ''): self
    {
        $instance = new self();
        $instance->addSuccess(...func_get_args());

        return $instance;
    }

    public function addSuccesses(int $count): self
    {
        for ($i = 0; $i < $count; $i++) {
            $this->addSuccess();
        }

        return $this;
    }

    public function addFails(int $count): self
    {
        for ($i = 0; $i < $count; $i++) {
            $this->addFail();
        }

        return $this;
    }

    public function addFail(string $stdErr = 'Fail', int $code = ResultCode::GENERAL_ERRORS, string $stdout = ''): self
    {
        $this->results[] = [
            'code' => $code,
            'error' => $stdErr,
            'result' => $stdout,
        ];

        return $this;
    }

    public function addSuccess(string $stdout = 'Success', string $stdErr = ''): self
    {
        $this->results[] = [
            'code' => ResultCode::OK,
            'error' => $stdErr,
            'result' => $stdout,
        ];

        return $this;
    }

    public function execute(ShellCommandInterface $command): CommandResult
    {
        $this->attempts++;

        $result = array_shift($this->results);

        if ($result === null) {
            throw new \RuntimeException('Unexpected command: ' . $command);
        }

        return new CommandResult(
            $command,
            Str::make($result['result']),
            new \DateTime(),
            Str::make($result['error']),
            $result['code']
        );
    }

    public function assertAttempts(int $expected): void
    {
        Assert::assertEquals($expected, $this->attempts);
    }
}
