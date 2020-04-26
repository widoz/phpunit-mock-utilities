<?php

declare(strict_types=1);

namespace Widoz\PhpUnit\Mock\Utilities\Tests\Unit\Stubs;

trait TraitStub
{
    public function publicTraitMethod()
    {
        return __FUNCTION__;
    }

    protected function protectedTraitMethod()
    {
        return __FUNCTION__;
    }

    private function privateTraitMethod()
    {
        return __FUNCTION__;
    }

    abstract public function abstractPublicTraitMethod();

    abstract protected function abstractProtectedTraitMethod();
}
