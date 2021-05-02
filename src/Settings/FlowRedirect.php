<?php

namespace ArtARTs36\ShellCommand\Settings;

use ArtARTs36\ShellCommand\Interfaces\ShellSettingInterface;

class FlowRedirect implements ShellSettingInterface
{
    protected $type;

    protected $flow;

    /**
     * @codeCoverageIgnore
     */
    public function __construct(int $type, string $flow)
    {
        $this->type = $type;
        $this->flow = $flow;
    }

    public function __toString(): string
    {
        return "$this->type > $this->flow";
    }
}
