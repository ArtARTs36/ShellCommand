<?php

namespace ArtARTs36\ShellCommand\Result;

class ResultCode
{
    public const OK = 0;
    public const GENERAL_ERRORS = 1;
    public const IS_NOT_EXECUTABLE = 126;
    public const COMMAND_NOT_FOUND = 127;
    public const USER_TERMINATED = 130;
}
