<?php

declare(strict_types=1);

namespace App\Service\Entity;

interface EntityInterface
{
    /**
     * TODO PHP7.4; public function hydrate(array $data = []): self;
     * @param array $data
     *
     * @return self
     * @noinspection ReturnTypeCanBeDeclaredInspection
     */
    public function hydrate(array $data = []);

    public function toArray(): array;
}
