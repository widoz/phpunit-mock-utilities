<?php

declare(strict_types=1);

namespace Widoz\PhpUnit\Mock\Utilities\Tests\Unit;

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
        $methods = ['methodForMock'];
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
        $methods = ['methodForMock'];

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
}
