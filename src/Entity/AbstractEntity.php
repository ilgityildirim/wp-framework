<?php

declare(strict_types=1);

namespace TripleBits\WpFramework\Entity;

use Serializable;
use JsonSerializable;
use TripleBits\WpFramework\Traits\ArrayableTrait;
use TripleBits\WpFramework\Traits\HydrateTrait;

abstract class AbstractEntity implements EntityInterface, Serializable, JsonSerializable
{
    use HydrateTrait;
    use ArrayableTrait;

    /**
     * @inheritDoc
     */
    public function serialize()
    {
        return serialize($this->toArray());
    }

    /**
     * @inheritDoc
     */
    public function unserialize($serialized)
    {
        /** @noinspection UnserializeExploitsInspection */
        $unserialized = unserialize($serialized);

        if (!$unserialized || !is_array($unserialized)) {
            return;
        }

        $this->hydrate($unserialized);
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
