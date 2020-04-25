<?php

declare(strict_types=1);

namespace Widoz\PhpUnit\Mock\Utilities\Tests\Unit\Stubs;

abstract class AbstractStub
{
    abstract public function abstractPublicMethod();

    abstract protected function abstractProtectedMethod();

    private function privateMethod(...$params)
    {
        return $params;
    }
}
