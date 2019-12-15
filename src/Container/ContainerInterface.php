<?php

declare(strict_types=1);

namespace TripleBits\WpFramework\Container;

interface ContainerInterface
{
    public function get(string $id): ?object;

    public function set(string $id, $value = null): void;

    public function has(string $id): bool;
}
