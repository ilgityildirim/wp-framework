<?php

declare(strict_types=1);

namespace TripleBits\WpFramework\Collection;

use JsonSerializable;

class OptionCollection extends Collection implements JsonSerializable
{

    public function doesContainBy(string $property, $value): bool
    {
        foreach ($this as $item) {
            $method = 'get' . ucfirst($property);
            if (method_exists($item, $method) && $value === $item->{$method}()) {
                return true;
            }
        }
        return false;
    }

    public function findBy(string $property, $value)
    {
        foreach ($this as $item) {
            $method = 'get' . ucfirst($property);
            if (method_exists($item, $method) && $value === $item->{$method}()) {
                return $item;
            }
        }

        return null;
    }

    public function removeBy(string $property, $value)
    {
        $item = $this->findBy($property, $value);
        if (!$item) {
            return;
        }
        $this->detach($item);
    }

    public function filterByPrefix(string $property, $prefix)
    {
        foreach ($this as $item) {
            $method = 'get' . ucfirst($property);
            if (method_exists($item, $method) && 0 !== strpos($item->{$method}(), $prefix)) {
                $this->detach($item);
            }
        }
    }

    public function sortBy(string $property, int $sort = SORT_DESC)
    {
        $array = $this->toArray();
        $columns = array_column($array, $property);
        array_multisort($columns, $sort, $array);
        $this->removeAll($this);
        $this->attachAllByArray($array);
    }
}
