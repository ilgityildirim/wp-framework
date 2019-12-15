<?php

declare(strict_types=1);

namespace TripleBits\WpFramework\Migration;

use SplObjectStorage;

class MigrationCollection extends SplObjectStorage
{
    public function filterByTimestamps(array $timestamps): self
    {
        /** @var MigrationCommandInterface $item */
        foreach ($this as $item) {
            if (!in_array($item->getTimestamp(), $timestamps, true)) {
                $this->detach($item);
            }
        }

        return $this;
    }

    public function filterAllExceptByTimestamps(array $timestamps): self
    {
        /** @var MigrationCommandInterface $item */
        foreach ($this as $item) {
            if (in_array($item->getTimestamp(), $timestamps, true)) {
                $this->detach($item);
            }
        }

        return $this;
    }
}
