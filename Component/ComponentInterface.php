<?php

declare(strict_types=1);

namespace App\Service\Component;


interface ComponentInterface
{
    public function registerHooks(): void;
}
