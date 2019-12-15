<?php

declare(strict_types=1);

namespace TripleBits\WpFramework\Adapter\Dto;

class HookDto
{
    public const DEFAULT_PRIORITY = 10;
    public const DEFAULT_ACCEPTED_ARGS = 0;

    /** @var string */
    private $hook;

    /** @var object */
    private $component;

    /** @var string */
    private $callback;

    /** @var int */
    private $priority = self::DEFAULT_PRIORITY;

    /** @var int */
    private $acceptedArgs = self::DEFAULT_ACCEPTED_ARGS;

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
    public function setPriority(int $priority = self::DEFAULT_PRIORITY): void
    {
        $this->priority = $priority;
    }

    public function getAcceptedArgs(): int
    {
        return $this->acceptedArgs;
    }

    /** @noinspection PhpUnused */
    public function setAcceptedArgs(int $acceptedArgs = self::DEFAULT_ACCEPTED_ARGS): void
    {
        $this->acceptedArgs = $acceptedArgs;
    }
}
