<?php

declare(strict_types=1);

namespace TripleBits\WpFramework\Container;

use ReflectionClass;
use ReflectionException;
use ReflectionParameter;

class Container implements ContainerInterface
{
    /** @var array */
    private $container = [];

    /** @var array */
    private $parameters = [];

    /** @var array */
    private $mapping = [];

    public function __construct(array $config = [])
    {
        $this->setContainerByConfig($config);
        $this->setParametersByConfig($config);
        $this->setMappingByConfig($config);
    }

    public function get(string $id): ?object
    {
        if ($id === self::class) {
            return $this;
        }

        if (!$this->has($id)) {
            return $this->loadClass($id);
        }

        if (is_object($this->container[$id])) {
            return $this->container[$id];
        }

        if (is_callable($this->container[$id])) {
            $this->container[$id] = $this->container[$id]();
        }

        $this->loadClass($id);

        return $this->container[$id];
    }

    /**
     * @param string $id
     * @param object|callable|null $value
     */
    public function set(string $id, $value = null): void
    {
        $this->container[$id] = $value;
    }

    public function remove(string $id): void
    {
        if (!$this->has($id)) {
            return;
        }

        unset($this->container[$id]);
    }

    public function has(string $id): bool
    {
        return isset($this->container[$id]);
    }

    public function setInitialized(string $id, array $options = []): void
    {
        $this->container[$id] = $options;
        $this->get($id);
    }

    /**
     * @noinspection PhpUnused
     * @param string $key
     * @param mixed $value
     *
     */
    public function setParameter(string $key, $value): void
    {
        $this->parameters[$key] = $value;
    }

    /**
     * @param string|null $key
     * @param mixed|null $default
     *
     * @return mixed|null
     */
    public function getParameter(?string $key = null, $default = null)
    {
        if (!$key) {
            return $this->parameters;
        }

        if ($this->hasParameter($key)) {
            return $this->parameters[$key];
        }

        if (false === strpos($key, '.')) {
            return $default;
        }

        $params = $this->parameters;
        foreach (explode('.', $key) as $segment) {
            if (!is_array($params) || !$this->hasParameter($segment)) {
                return $default;
            }

            $items = &$items[$segment];
        }

        return $params;
    }

    public function hasParameter(string $key): bool
    {
        return isset($this->parameters[$key]);
    }

    private function setContainerByConfig(array $config = []): void
    {
        if (!isset($config['services'])) {
            return;
        }

        foreach ($config['services'] as $key => $value) {
            if (is_int($key) && is_string($value)) {
                $key = $value;
            }
            $this->container[$key] = $value;
        }
    }

    private function setParametersByConfig(array $config = []): void
    {
        if (!isset($config['params'])) {
            return;
        }

        foreach ($config['params'] as $key => $value) {
            $this->parameters[$key] = $value;
        }
    }

    private function setMappingByConfig(array $config = []): void
    {
        if (!isset($config['mapping'])) {
            return;
        }

        foreach ($config['mapping'] as $key => $value) {
            if (is_scalar($value)) {
                $this->mapping[$key] = $value;
            }
        }
    }

    /**
     * @param string $id
     *
     * @return object|string
     * @throws InvalidConstructorParamException
     * @throws ReflectionException
     */
    private function resolve(string $id)
    {
        if (!class_exists($id)) {
            return $id;
        }

        $reflection = $this->getReflection($id);

        if (!$reflection) {
            return $id;
        }

        return $this->newInstance($reflection);
    }

    private function getReflection(string $class)
    {
        try {
            return new ReflectionClass($class);
        }
        catch (ReflectionException $e) {
            return null;
        }
    }

    /**
     * @param ReflectionClass|null $reflection
     *
     * @return object|null
     * @throws InvalidConstructorParamException
     * @throws ReflectionException
     */
    private function newInstance(?ReflectionClass $reflection)
    {
        if (!$reflection) {
            return null;
        }

        if (!$reflection->getConstructor()) {
            return $reflection->newInstance();
        }

        $params = $reflection->getConstructor()->getParameters();
        $options = $this->container[$reflection->getName()] ?? [];

        if (!is_array($options)) {
            $options = [];
        }

        $args = [];
        foreach ($params as $param) {
            $args[] = $this->getConstructorParam($param, $options);
        }

        return $reflection->newInstanceArgs($args);
    }

    /**
     * @param ReflectionParameter $param
     * @param array $options
     *
     * @return mixed|object|null
     * @throws InvalidConstructorParamException
     * @throws ReflectionException
     */
    private function getConstructorParam(ReflectionParameter $param, array $options = [])
    {
        if ($param->getClass()) {
            return $this->get($param->getClass()->name);
        }

        if (isset($options[$param->getName()])) {
            return $this->getConstructorParamValue($options[$param->getName()]);
        }

        if (isset($this->parameters[$param->getName()])) {
            return $this->getConstructorParamValue($this->parameters[$param->getName()]);
        }

        if ($param->isDefaultValueAvailable()) {
            return $param->getDefaultValue();
        }

        if (!$param->getType() || $param->getType()->allowsNull()) {
            return null;
        }

        throw new InvalidConstructorParamException($param->getName());
    }

    /**
     * @param null|array|string $value
     *
     * @return null|object|array|string
     */
    private function getConstructorParamValue($value = null)
    {
        if (!is_string($value)) {
            return $value;
        }

        if (class_exists($value)) {
            return $this->get($value);
        }

        if (!preg_match_all('#{{(.*?)}}#', $value, $matches)) {
            return $value;
        }

        $replace = [];

        foreach ($matches[1] as $key) {
            $replace[] = $this->getParameter($key);
        }

        return str_replace($matches[0], $replace, $value);
    }

    private function loadClass(string $id): ?object
    {
        if (!class_exists($id)) {
            return null;
        }

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->container[$id] = $this->resolve($id);

        return $this->container[$id];
    }
}
