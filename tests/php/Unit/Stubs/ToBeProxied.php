<?php

declare(strict_types=1);

namespace Widoz\PhpUnit\Mock\Utilities\Tests\Unit\Stubs;

final class ToBeProxied
{
    public static $publicStaticProperty = 'publicStaticProperty';

    protected static $protectedStaticProperty = 'protectedStaticProperty';

    private static $privateStaticProperty = 'privateStaticProperty';

    public $publicProperty = 'publicProperty';

    protected $protectedProperty = 'protectedProperty';

    private $privateProperty = 'privateProperty';

    public static function publicStaticMethod(): string
    {
        return __FUNCTION__;
    }

    protected static function protectedStaticMethod(): string
    {
        return __FUNCTION__;
    }

    private static function privateStaticMethod(): string
    {
        return __FUNCTION__;
    }

    public function publicMethod(): string
    {
        return __FUNCTION__;
    }

    protected function protectedMethod(): string
    {
        return __FUNCTION__;
    }

    private function privateMethod(): string
    {
        return __FUNCTION__;
    }
}
