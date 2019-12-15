<?php

declare(strict_types=1);

namespace TripleBits\WpFramework\Component;


interface ComponentInterface
{
    public function registerHooks(): void;
}
