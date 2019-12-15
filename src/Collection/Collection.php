<?php

declare(strict_types=1);

namespace TripleBits\WpFramework\Collection;

use SplObjectStorage;
use JsonSerializable;

class Collection extends SplObjectStorage implements JsonSerializable
{
    /** @var string */
    protected $storedClass;

    public function __construct(string $storedClass)
    {
        $this->storedClass = $storedClass;
    }

    public function toArray(): array
    {
        $collection = [];
        foreach ($this as $item) {
            if (method_exists($item, 'toArray')) {
                $collection[] = $item->toArray();
            } else {
                $collection[] = $item;
            }
        }

        return $collection;
    }

    public function attachAllByArray(array $data = []): void
    {
        foreach ($data as $item) {
            if ($item instanceof $this->storedClass) {
                $this->attach($item);
                continue;
            }

            $object = new $this->storedClass;

            if (!method_exists($object, 'hydrate')) {
                throw new InvalidCollectionItemException(sprintf('Class %s does', $this->storedClass));
            }

            $object->hydrate((array) $item);
            $this->attach($object);
        }
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
