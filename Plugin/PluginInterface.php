<?php

declare(strict_types=1);

namespace App\Service\Plugin;

use App\Service\Container\Container;

interface PluginInterface
{
    public function __construct(Container $container);

    public function init(): void;

    public function getSlug(): ?string;

    public function getContainer(): Container;

    public function addComponent(string $id, array $options = []): void;

    public function removeComponent(string $id): void;
}
