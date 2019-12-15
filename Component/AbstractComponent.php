<?php

declare(strict_types=1);

namespace App\Service\Component;

use App\Service\Adapter\Dto\HookDto;
use App\Service\Adapter\Hooks;

abstract class AbstractComponent implements ComponentInterface
{
    /** @var Hooks */
    private $hooks;

    public function __construct(Hooks $hooks)
    {
        $this->hooks = $hooks;
        $this->registerHooks();
    }

    public function addAction(string $action, string $method, int $acceptedArgs = 0, int $priority = 10): void
    {
        $dto = $this->generateDto($action, $method, $acceptedArgs, $priority);
        $this->hooks->addAction($dto);
    }

    public function addFilter(string $action, string $method, int $acceptedArgs = 0, int $priority = 10): void
    {
        $dto = $this->generateDto($action, $method, $acceptedArgs, $priority);
        $this->hooks->addFilter($dto);
    }

    private function generateDto(string $action, string $method, int $acceptedArgs = 0, int $priority = 10): HookDto
    {
        $dto = new HookDto;
        $dto->setHook($action);
        $dto->setComponent($this);
        $dto->setCallback($method);
        $dto->setAcceptedArgs($acceptedArgs);
        $dto->setPriority($priority);

        return $dto;
    }
}
