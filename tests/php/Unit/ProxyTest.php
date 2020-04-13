<?php

declare(strict_types=1);

namespace Widoz\PhpUnit\Mock\Utilities\Tests\Unit;

use ReflectionException;
use ReflectionProperty;
use Widoz\PhpUnit\Mock\Utilities\Proxy;
use Widoz\PhpUnit\Mock\Utilities\TestCase;
use Widoz\PhpUnit\Mock\Utilities\Tests\Unit\Stubs\ToBeProxied;

/**
 * @test Proxy class
 */
final class ProxyTest extends TestCase
{
    /**
     * A new proxy instance is created correctly by passing a class name
     */
    public function testNewProxyViaClassName(): void
    {
        $proxy = new Proxy(ToBeProxied::class);
        [$classProperty, $objectProperty] = $this->proxyClassReflectedProperties($proxy);

        parent::assertEquals(ToBeProxied::class, $classProperty->getValue($proxy));
        parent::assertEquals(null, $objectProperty->getValue($proxy));
    }

    /**
     * A new proxy instance is created correctly by passing an object
     */
    public function testNewProxyViaObject(): void
    {
        $toBeProxied = new ToBeProxied();
        $proxy = new Proxy($toBeProxied);
        [$classProperty, $objectProperty] = $this->proxyClassReflectedProperties($proxy);

        parent::assertEquals(ToBeProxied::class, $classProperty->getValue($proxy));
        parent::assertEquals($toBeProxied, $objectProperty->getValue($proxy));
    }

    /**
     * Can call methods of the proxied object
     */
    public function testMethodsOfProxiedObjectAreAccessible(): void
    {
        $toBeProxied = new ToBeProxied();
        $proxy = new Proxy($toBeProxied);

        parent::assertEquals('publicMethod', $proxy->publicMethod());
        parent::assertEquals('protectedMethod', $proxy->protectedMethod());
        parent::assertEquals('privateMethod', $proxy->privateMethod());
    }

    /**
     * Can call static methods of the proxied class
     */
    public function testStaticMethodsOfProxiedClassAreAccessible(): void
    {
        $proxy = new Proxy(ToBeProxied::class);

        parent::assertEquals('publicStaticMethod', $proxy->publicStaticMethod());
        parent::assertEquals('protectedStaticMethod', $proxy->protectedStaticMethod());
        parent::assertEquals('privateStaticMethod', $proxy->privateStaticMethod());
    }

    /**
     * Can access to proxied properties
     */
    public function testCanAccessToProxiedProperties(): void
    {
        $toBeProxied = new ToBeProxied();
        $proxy = new Proxy($toBeProxied);

        parent::assertEquals('publicProperty', $proxy->publicProperty);
        parent::assertEquals('protectedProperty', $proxy->protectedProperty);
        parent::assertEquals('privateProperty', $proxy->privateProperty);
    }

    /**
     * Can access to proxied static properties
     */
    public function testCanAccessToProxiedStaticProperties(): void
    {
        $proxy = new Proxy(ToBeProxied::class);

        parent::assertEquals('publicStaticProperty', $proxy->publicStaticProperty);
        parent::assertEquals('protectedStaticProperty', $proxy->protectedStaticProperty);
        parent::assertEquals('privateStaticProperty', $proxy->privateStaticProperty);
    }

    /**
     * It's possible to set properties on proxied object
     */
    public function testCanSetPropertiesOnProxiedProperty(): void
    {
        $toBeProxied = new ToBeProxied();
        $proxy = new Proxy($toBeProxied);

        parent::assertEquals('publicProperty', $proxy->publicProperty);
        parent::assertEquals('protectedProperty', $proxy->protectedProperty);
        parent::assertEquals('privateProperty', $proxy->privateProperty);

        $proxy->publicProperty = 'newPublicProperty';
        $proxy->protectedProperty = 'newProtectedProperty';
        $proxy->privateProperty = 'newPrivateProperty';

        parent::assertEquals('newPublicProperty', $proxy->publicProperty);
        parent::assertEquals('newProtectedProperty', $proxy->protectedProperty);
        parent::assertEquals('newPrivateProperty', $proxy->privateProperty);
    }

    /**
     * @param Proxy $proxy
     *
     * @throws ReflectionException
     * @return array
     */
    private function proxyClassReflectedProperties(Proxy $proxy): array
    {
        $classProperty = new ReflectionProperty($proxy, 'class');
        $classProperty->setAccessible(true);
        $objectProperty = new ReflectionProperty($proxy, 'object');
        $objectProperty->setAccessible(true);

        return [
            $classProperty,
            $objectProperty,
        ];
    }
}
