<?php

declare(strict_types=1);

namespace Widoz\PhpUnit\Mock\Utilities\Tests\Unit;

use Faker\Factory;
use Faker\Generator;
use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use ReflectionClass;
use Widoz\PhpUnit\Mock\Utilities\Faker;
use Widoz\PhpUnit\Mock\Utilities\Proxy;
use Widoz\PhpUnit\Mock\Utilities\TestCase;
use Widoz\PhpUnit\Mock\Utilities\Tests\Unit\Stubs\AbstractStub;
use Widoz\PhpUnit\Mock\Utilities\Tests\Unit\Stubs\ClassStub;

use Widoz\PhpUnit\Mock\Utilities\Tests\Unit\Stubs\TraitStub;

use function array_intersect;

/**
 * @test TestCase
 */
final class TestCaseTest extends TestCase
{
    /**
     * Can create a mock with properties and methods
     * Assert it's possible to call a mocked method
     */
    public function testMock(): void
    {
        $methods = ['publicMethod'];
        $mock = parent::mock(ClassStub::class, [], $methods);

        self::assertMethodsExists($mock, ...$methods);
    }

    /**
     * Test mock for Abstract classes
     */
    public function testMockForAbstractClasses(): void
    {
        $methodsToMock = [
            'abstractPublicMethod',
            'abstractProtectedMethod',
            'privateMethod',
        ];
        $mock = parent::mock(
            AbstractStub::class,
            [],
            $methodsToMock
        );

        parent::assertInstanceOf(MockObject::class, $mock);
        parent::assertInstanceOf(AbstractStub::class, $mock);
        self::assertMethodsExists($mock, ...$methodsToMock);
    }

    /**
     * Test mock can be created for trait
     */
    public function testMockForTrait(): void
    {
        $methodsToMock = [
            'publicTraitMethod',
            'protectedTraitMethod',
            'privateTraitMethod',
            'abstractPublicTraitMethod',
            'abstractProtectedTraitMethod',
        ];
        $mock = parent::mock(
            TraitStub::class,
            [],
            $methodsToMock
        );

        parent::assertInstanceOf(MockObject::class, $mock);
        self::assertMethodsExists($mock, ...$methodsToMock);
    }

    /**
     * Test createMockBuilder
     */
    public function testCreateMockBuilder(): void
    {
        $property = Faker::faker()->uuid;
        $constructorArguments = [$property];
        $methods = ['publicMethod'];

        /** @var TestCase $proxy */
        $proxy = new Proxy(new TestCase());
        $mockBuilder = $proxy->createMockBuilder(
            ClassStub::class,
            $constructorArguments,
            $methods
        );

        $proxyMockBuilder = new Proxy($mockBuilder);
        parent::assertEquals($constructorArguments, $proxyMockBuilder->constructorArgs);
        parent::assertEquals($methods, $proxyMockBuilder->methods);
    }

    /**
     * Test createMockBuilder does not set constructor arguments if not given
     */
    public function testCreateMockBuilderDoesNotSetConstructorArguments(): void
    {
        /** @var TestCase $proxy */
        $proxy = new Proxy(new TestCase());
        $mockBuilder = $proxy->createMockBuilder(
            ClassStub::class,
            [],
            []
        );

        $proxyMockBuilder = new Proxy($mockBuilder);

        parent::assertEquals([], $proxyMockBuilder->constructorArgs);
    }

    /**
     * Test createMockBuilder does not set the methods if not given
     */
    public function testCreateMockBuilderDoesNotSetMethods(): void
    {
        $methods = [];

        /** @var TestCase $proxy */
        $proxy = new Proxy(new TestCase());
        $mockBuilder = $proxy->createMockBuilder(
            ClassStub::class,
            [],
            $methods
        );

        $proxyMockBuilder = new Proxy($mockBuilder);

        parent::assertEquals($methods, $proxyMockBuilder->methods);
    }

    /**
     * Test will return automatically use `returnCallback`
     */
    public function testWillReturnCallback(): void
    {
        $argument = self::faker()->uuid;

        /** @var ClassStub $mock */
        $mock = parent::mock(
            ClassStub::class,
            [],
            [
                'publicMethod' => function ($argument) {
                    return $argument;
                },
                'protectedMethod' => function () use ($argument) {
                    return $argument;
                },
            ]
        );

        $result = $mock->publicMethod($argument);
        parent::assertEquals($argument, $result);

        $result = $mock->publicMethodCallProtectedMethod();
        parent::assertEquals($argument, $result);
    }

    /**
     * Test will return automatically use `returnValue`
     */
    public function testWillReturnValue(): void
    {
        $argument = self::faker()->uuid;

        /** @var ClassStub $mock */
        $mock = parent::mock(
            ClassStub::class,
            [],
            [
                'publicMethod' => $argument,
                'protectedMethod' => $argument,
            ]
        );

        $result = $mock->publicMethod();
        parent::assertEquals($argument, $result);

        $result = $mock->publicMethodCallProtectedMethod();
        parent::assertEquals($argument, $result);
    }

    /**
     * Test return value not wrapped if already a stub
     */
    public function testValueNotWrappedTwiceIfAlreadyAStub(): void
    {
        $expectedResult = self::faker()->uuid;
        /** @var ClassStub $mock */
        $mock = parent::mock(
            ClassStub::class,
            [],
            [
                'publicMethod' => parent::returnValue($expectedResult),
            ]
        );

        $result = $mock->publicMethod();
        parent::assertEquals($expectedResult, $result);
    }

    private static function assertMethodsExists(object $object, string ...$methodNames): void
    {
        $reflection = new ReflectionClass($object);
        $methods = $reflection->getMethods();

        foreach ($methods as &$method) {
            $method = $method->getName();
        }
        unset($method);

        $intersection = array_intersect($methodNames, $methods);
        parent::assertEquals($methodNames, $intersection);
    }

    /**
     * Create a faker generator
     *
     * @throws InvalidArgumentException
     * @return Generator
     */
    private static function faker(): Generator
    {
        static $generator = null;

        if (!$generator) {
            $factory = new Factory();
            $generator = $factory->create();
        }

        return $generator;
    }
}
