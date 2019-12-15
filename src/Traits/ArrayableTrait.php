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
    public function toArray(bool $isMySql = true): array
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $reflection = new ReflectionClass($this);
        $props = $reflection->getProperties(
            ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED | ReflectionProperty::IS_PRIVATE
        );

        $data = [];
        /** @var ReflectionProperty $prop */
        foreach($props as $prop) {
            $data[$this->getName($prop, $isMySql)] = $this->getValue($prop, $isMySql);
        }

        return $data;
    }

    /**
     * @param ReflectionProperty $prop
     * @param bool $isMySql
     *
     * @return int|string
     */
    private function getValue(ReflectionProperty $prop, bool $isMySql = true)
    {
        $prop->setAccessible(true);
        $value = $prop->getValue($this);

        if (!$value instanceof DateTime) {
            return $value;
        }

        if ($isMySql) {
            return $value->format('Y-m-d H:i:s');
        }

        return (new DateTimeAdapter)->transformToWpFormat($value);
    }

    private function getName(ReflectionProperty $prop, bool $isMySql = true): string
    {
        if ($isMySql) {
            return (new Strings)->decamelize($prop->getName());
        }

        return $prop->getName();
    }
}
