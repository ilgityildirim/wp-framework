<?php

declare(strict_types=1);

namespace TripleBits\WpFramework\Traits;

use TripleBits\WpFramework\Utils\Strings;
use DateTime;
use ReflectionException;
use ReflectionMethod;
use TripleBits\WpFramework\Entity\EntityException;

trait HydrateTrait
{
    /**
     * @param array $data
     *
     * @return self
     * @noinspection PhpDocMissingThrowsInspection
     * @noinspection ReturnTypeCanBeDeclaredInspection
     */
    public function hydrate(?array $data = [])
    {
        foreach ($data as $key => $value) {
            /** @noinspection PhpUnhandledExceptionInspection */
            $this->hydrateByMethod('set' . (new Strings)->camelize($key), $value);
        }

        return $this;
    }

    /**
     * @param string $method
     * @param mixed $value
     *
     * @throws ReflectionException
     */
    private function hydrateByMethod(string $method, $value): void
    {
        /** @noinspection CallableParameterUseCaseInTypeContextInspection */
        $method = new ReflectionMethod($this, $method);

        $params = $method->getParameters();

        if (!isset($params[0]) || count($params) > 1) {
            throw new EntityException(sprintf(
                'Class %s setter method %s does not have a first parameter or has more than one parameter',
                static::class,
                $method
            ));
        }

        $param = $params[0];

        if ($value && !$value instanceof DateTime && $param->getClass() && 'DateTime' === $param->getClass()->getName()) {
            /** @noinspection PhpUnhandledExceptionInspection */
            $value = new DateTime($value);
        }

        $method->invoke($this, $value);
    }
}
