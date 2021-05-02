<?php

namespace ArtARTs36\ShellCommand\Settings;

use ArtARTs36\ShellCommand\Interfaces\ShellSettingInterface;

class FlowRedirect implements ShellSettingInterface
{
    protected $type;

    protected $redirect;

    /**
     * @codeCoverageIgnore
     */
    public function __construct(int $type, string $flow)
    {
        $this->type = $type;
        $this->redirect = $flow;
    }

    public function __toString(): string
    {
        return "$this->type>$this->redirect";
    }
}
