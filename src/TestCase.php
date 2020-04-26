<?php

declare(strict_types=1);

namespace Widoz\PhpUnit\Mock\Utilities;

use PHPUnit\Framework\Exception;
use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\RuntimeException;
use PHPUnit\Framework\MockObject\Stub\Stub;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use ReflectionClass;
use ReflectionException;

use function is_callable;

/**
 * Phpunit Test Case
 */
class TestCase extends PHPUnitTestCase
{
    /**
     * Create a mock object
     *
     * @param array<mixed> $constructorArguments
     * @param array<string,mixed> $methods
     *
     * @throws Exception
     * @throws ReflectionException
     * @throws RuntimeException
     * @return MockObject
     */
    final protected function mock(
        string $className,
        array $constructorArguments,
        array $methods
    ): MockObject {
        $mockBuilder = $this->createMockBuilder($className, $constructorArguments, $methods);
        $mock = $this->buildMock($className, $mockBuilder);

        if (self::isAssociativeArray($methods)) {
            self::configureMock($mock, $methods);
        }

        return $mock;
    }

    /**
     * Build the Sut Mock Object
     * Basic configuration available for all of the sut objects, call `getMock` to get the mock.
     *
     * @param array<mixed> $constructorArguments
     * @param array<string> $methods
     *
     * @throws RuntimeException
     */
    private function createMockBuilder(
        string $className,
        array $constructorArguments,
        array $methods
    ): MockBuilder {
        $mockBuilder = parent::getMockBuilder($className);

        $constructorArguments
            ? $mockBuilder->setConstructorArgs($constructorArguments)
            : $mockBuilder->disableOriginalConstructor();

        $methodsName = self::isAssociativeArray($methods) ? array_keys($methods) : $methods;

        if ($methods) {
            $mockBuilder->onlyMethods($methodsName);
        }

        return $mockBuilder;
    }

    /**
     * Create a mock object by the class type
     * The method will create the right mock object based on the type of the class. Eg.
     * interfaces, traits or abstract classes.
     *
     * @param string $className
     * @param MockBuilder $mockBuilder
     *
     * @throws Exception
     * @throws ReflectionException
     * @throws RuntimeException
     * @return MockObject
     */
    private static function buildMock(string $className, MockBuilder $mockBuilder): MockObject
    {
        $mock = null;
        $reflection = new ReflectionClass($className);

        if (!$reflection->isTrait() && ($reflection->isAbstract() || $reflection->isInterface())) {
            $mock = $mockBuilder->getMockForAbstractClass();
        }
        if ($reflection->isTrait()) {
            $mock = $mockBuilder->getMockForTrait();
        }
        if (!$mock) {
            $mock = $mockBuilder->getMock();
        }

        return $mock;
    }

    /**
     * Check if the given array is a map or not
     *
     * @param array<mixed> $array
     */
    private static function isAssociativeArray(array $array): bool
    {
        if ($array === []) {
            return false;
        }

        return array_keys($array) !== range(0, count($array) - 1);
    }

    /**
     * @param array<string, mixed> $methods
     */
    private static function configureMock(MockObject $mockObject, array $methods): void
    {
        foreach ($methods as $methodName => $return) {
            $mockObject->method($methodName)->will(self::createReturnType($return));
        }
    }

    /**
     * @param $return
     *
     * @return mixed
     */
    private static function createReturnType($return)
    {
        if ($return instanceof Stub) {
            return $return;
        }

        if (is_callable($return)) {
            return parent::returnCallback($return);
        }

        return parent::returnValue($return);
    }

    /**
     * Retrieve Proxy instance from a mock
     *
     * @param string $className
     * @param array<mixed> $constructorArguments
     * @param array<string> $methods
     *
     * @throws Exception
     * @throws ReflectionException
     * @throws RuntimeException
     * @return Proxy
     */
    final protected function proxyMock(
        string $className,
        array $constructorArguments,
        array $methods
    ): Proxy {
        return new Proxy($this->mock($className, $constructorArguments, $methods));
    }
}
