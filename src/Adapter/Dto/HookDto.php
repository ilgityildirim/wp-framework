<?php

declare(strict_types=1);

namespace TripleBits\WpFramework\Adapter\Dto;

class HookDto
{
    /** @var string */
    private $hook;

    /** @var object */
    private $component;

    /** @var string */
    private $callback;

    /** @var int */
    private $priority = 10;

    /** @var int */
    private $acceptedArgs = 0;

    public function getHook(): string
    {
        return $this->hook;
    }

    /** @noinspection PhpUnused */
    public function setHook(string $hook): void
    {
        $this->hook = $hook;
    }

    public function getComponent(): object
    {
        return $this->component;
    }

    /** @noinspection PhpUnused */
    public function setComponent(object $component): void
    {
        $this->component = $component;
    }

    public function getCallback(): string
    {
        return $this->callback;
    }

    /** @noinspection PhpUnused */
    public function setCallback(string $callback): void
    {
        $this->callback = $callback;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    /** @noinspection PhpUnused */
    public function setPriority(int $priority = 10): void
    {
        $this->priority = $priority;
    }

    public function getAcceptedArgs(): int
    {
        return $this->acceptedArgs;
    }

    /** @noinspection PhpUnused */
    public function setAcceptedArgs(int $acceptedArgs = 0): void
    {
        $this->acceptedArgs = $acceptedArgs;
    }
}
