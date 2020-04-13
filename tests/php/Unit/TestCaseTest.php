<?php

declare(strict_types=1);

namespace Widoz\PhpUnit\Mock\Utilities\Tests\Unit;

use Widoz\PhpUnit\Mock\Utilities\Faker;
use Widoz\PhpUnit\Mock\Utilities\Proxy;
use Widoz\PhpUnit\Mock\Utilities\TestCase;
use Widoz\PhpUnit\Mock\Utilities\Tests\Unit\Stubs\ToBeTested;

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
     * Test mockBuilder
     */
    public function testMockBuilder(): void
    {
        $property = Faker::faker()->uuid;
        $constructorArguments = [$property];
        $methods = ['methodForMock'];

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
     * Test mockBuilder does not set constructor arguments if not given
     */
    public function testMockBuilderDoesNotSetConstructorArguments(): void
    {
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
     * Test mockBuilder does not set the methods if not given
     */
    public function testMockBuilderDoesNotSetMethods(): void
    {
        $methods = ['methodForMock'];

        $proxy = new Proxy(new TestCase());
        $mockBuilder = $proxy->createMockBuilder(
            ToBeTested::class,
            [],
            $methods
        );

        $proxyMockBuilder = new Proxy($mockBuilder);

        parent::assertEquals($methods, $proxyMockBuilder->methods);
    }
}
