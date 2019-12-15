<?php

declare(strict_types=1);

namespace TripleBits\WpFramework\Entity;

interface EntityInterface
{
    /**
     * < PHP 7.4 support block
     * @param array $data
     *
     * @return self
     * @noinspection ReturnTypeCanBeDeclaredInspection
     */
    public function hydrate(array $data = []);

    public function toArray(): array;
}
