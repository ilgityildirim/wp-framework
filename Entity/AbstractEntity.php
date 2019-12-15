<?php

declare(strict_types=1);

namespace App\Service\Entity;

use ReflectionClass;
use ReflectionProperty;
use Serializable;
use JsonSerializable;
use ReflectionMethod;
use ReflectionException;

abstract class AbstractEntity implements EntityInterface, Serializable, JsonSerializable
{
    public function hydrate(array $data = []): self
    {
        foreach ($data as $key => $value) {
            /** @noinspection PhpUnhandledExceptionInspection */
            $this->hydrateByMethod('set' . ucfirst($key), $value);
        }

        return $this;
    }

    public function toArray(): array
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $ref = new ReflectionClass($this);
        $tmpProps = $ref->getProperties(
            ReflectionProperty::IS_PUBLIC
            | ReflectionProperty::IS_PROTECTED
            | ReflectionProperty::IS_PRIVATE
        );

        $data = [];
        foreach ($tmpProps as $prop) {
            $data[$prop->getName()] = $prop->getValue($this);
        }

        return $data;
    }

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

    /**
     * @param string $method
     * @param mixed $value
     *
     * @throws ReflectionException
     */
    private function hydrateByMethod($method, $value)
    {
        /** @noinspection CallableParameterUseCaseInTypeContextInspection */
        $method = new ReflectionMethod($this, $method);

        // TODO PHP5.4; $param = $method->getParameters()[0];
        $params = $method->getParameters();

        if (!isset($params[0]) || count($params) > 1) {
            throw new EntityException(sprintf(
                'Class %s setter method %s does not have a first parameter or has more than one parameter',
                static::class,
                $method
            ));
        }

        $param = $params[0];

        if ('DateTime' === $param->getType()) {
            $this->$method($value);
        }

        $this->$method($value);
    }
}
