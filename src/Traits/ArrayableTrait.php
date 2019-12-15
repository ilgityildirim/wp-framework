<?php

declare(strict_types=1);

namespace TripleBits\WpFramework\Traits;

use TripleBits\WpFramework\Utils\Strings;
use DateTime;
use ReflectionClass;
use ReflectionProperty;
use TripleBits\WpFramework\Adapter\DateTimeAdapter;

trait ArrayableTrait
{
    public function toArray(bool $isScalar = true): array
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $reflection = new ReflectionClass($this);
        $props = $reflection->getProperties(
            ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED | ReflectionProperty::IS_PRIVATE
        );

        $data = [];
        /** @var ReflectionProperty $prop */
        foreach($props as $prop) {
            $data[$this->_getPropName($prop, $isScalar)] = $this->_getPropValue($prop, $isScalar);
        }

        return $data;
    }

    /**
     * @param ReflectionProperty $prop
     * @param bool $isScalar
     *
     * @return int|string
     */
    private function _getPropValue(ReflectionProperty $prop, bool $isScalar = true)
    {
        $prop->setAccessible(true);
        $value = $prop->getValue($this);

        if (is_object($value) && method_exists($value, 'toArray')) {
            return $value->toArray();
        }

        if (!$value instanceof DateTime) {
            return $value;
        }

        if ($isScalar) {
            return $value->format('Y-m-d H:i:s');
        }

        return (new DateTimeAdapter)->transformToWpFormat($value);
    }

    private function _getPropName(ReflectionProperty $prop, bool $isScalar = true): string
    {
        if ($isScalar) {
            return Strings::decamelize($prop->getName());
        }

        return $prop->getName();
    }
}
