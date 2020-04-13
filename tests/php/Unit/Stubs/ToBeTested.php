<?php

declare(strict_types=1);

namespace Widoz\PhpUnit\Mock\Utilities\Tests\Unit\Stubs;

class ToBeTested
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

    public function methodForMock(): string
    {
        return __METHOD__;
    }

    public function neverMockedMethod(): string
    {
        return __FUNCTION__;
    }
}
