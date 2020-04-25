<?php

declare(strict_types=1);

namespace Widoz\PhpUnit\Mock\Utilities\Tests\Unit;

use PHPUnit\Framework\MockObject\MockObject;
use ReflectionClass;
use Widoz\PhpUnit\Mock\Utilities\Faker;
use Widoz\PhpUnit\Mock\Utilities\Proxy;
use Widoz\PhpUnit\Mock\Utilities\TestCase;
use Widoz\PhpUnit\Mock\Utilities\Tests\Unit\Stubs\AbstractStub;
use Widoz\PhpUnit\Mock\Utilities\Tests\Unit\Stubs\ToBeTested;

use function array_intersect;

/**
 * @test TestCase
 */
final class TestCaseTest extends TestCase
{
    /**
     * Can create a mock with properties and methods
     */
    public function testMock(): void
    {
        $property = Faker::faker()->uuid;
        $constructorArguments = [$property];

        $returnValueOfMockedMethod = Faker::faker()->uuid;
        $methods = [
            'methodForMock' => parent::returnCallback(
                function () use ($returnValueOfMockedMethod): string {
                    return $returnValueOfMockedMethod;
                }
            ),
        ];

        /** @var TestCase $testCase */
        $testCase = new Proxy(new TestCase());
        $mock = $testCase->mock(
            ToBeTested::class,
            $constructorArguments,
            $methods
        );

        $mockedValueResult = $mock->methodForMock();

        parent::assertEquals($property, $mock->property());
        parent::assertEquals($mockedValueResult, $returnValueOfMockedMethod);
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
            ToBeTested::class,
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
            ToBeTested::class,
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
            ToBeTested::class,
            [],
            $methods
        );

        $proxyMockBuilder = new Proxy($mockBuilder);

        parent::assertEquals($methods, $proxyMockBuilder->methods);
    }

    /**
     * Test createMockBuilder for Abstract classes
     */
    public function testCreateMockBuilderForAbstractClasses(): void
    {
        $methodsToMock = [
            'abstractPublicMethod',
            'abstractProtectedMethod',
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

    private function assertMethodsExists(object $object, string ...$methodNames): void
    {
        $reflection = new ReflectionClass($object);
        $methods = $reflection->getMethods();

        foreach ($methods as &$method) {
            $method = $method->getName();
        }

        $intersection = array_intersect($methodNames, $methods);
        parent::assertEquals($methodNames, $intersection);
    }
}
