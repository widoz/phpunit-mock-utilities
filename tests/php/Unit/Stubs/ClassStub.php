<?php

declare(strict_types=1);

namespace Widoz\PhpUnit\Mock\Utilities\Tests\Unit\Stubs;

class ClassStub
{
    /**
     * @var string
     */
    private $property;

    public function __construct(string $property)
    {
        $this->property = $property;
    }

    public function property(): string
    {
        return $this->property;
    }

    public function publicMethod(): string
    {
        return __METHOD__;
    }

    public function publicMethodCallProtectedMethod(): string
    {
        return $this->protectedMethod();
    }

    protected function protectedMethod(): string
    {
        return __METHOD__;
    }

    private function privateMethod(): string
    {
        return __METHOD__;
    }
}
