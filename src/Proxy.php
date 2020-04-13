<?php

namespace Widoz\PhpUnit\Mock\Utilities;

use ReflectionException;
use ReflectionMethod;
use ReflectionProperty;

/**
 * Proxy
 * Class extracted from ptrofimov/xpmock
 *
 * @link https://github.com/ptrofimov/xpmock
 * @see https://github.com/ptrofimov/xpmock/blob/master/src/Xpmock/Reflection.php
 */
final class Proxy
{
    /**
     * @var string
     */
    private $class;

    /**
     * @var mixed
     */
    private $object;

    /**
     * Proxy constructor
     *
     * @param mixed $classOrObject
     */
    public function __construct($classOrObject)
    {
        [$this->class, $this->object] = is_object($classOrObject)
            ? [get_class($classOrObject), $classOrObject]
            : [(string)$classOrObject, null];
    }

    /**
     * @param string $key
     *
     * @throws ReflectionException
     * @return mixed
     */
    public function __get(string $key)
    {
        $property = new ReflectionProperty($this->class, $key);

        if (!$property->isPublic()) {
            $property->setAccessible(true);
        }

        return $property->isStatic()
            ? $property->getValue()
            : $property->getValue($this->object);
    }

    /**
     * @param string $key
     * @param mixed $value
     *
     * @throws ReflectionException
     * @return $this
     */
    public function __set(string $key, $value): self
    {
        $property = new ReflectionProperty($this->class, $key);

        if (!$property->isPublic()) {
            $property->setAccessible(true);
        }

        $property->isStatic()
            ? $property->setValue($value)
            : $property->setValue($this->object, $value);

        return $this;
    }

    /**
     * @param string $methodName
     * @param array $args
     *
     * @throws ReflectionException
     * @return mixed
     */
    public function __call($methodName, array $args)
    {
        $method = new ReflectionMethod($this->class, $methodName);

        if (!$method->isPublic()) {
            $method->setAccessible(true);
        }

        return $method->isStatic()
            ? $method->invokeArgs(null, $args)
            : $method->invokeArgs($this->object, $args);
    }
}
