<?php

declare(strict_types=1);

namespace Widoz\PhpUnit\Mock\Utilities\Tests\Unit\Stubs;

trait TraitStub
{
    public function publicTraitMethod()
    {
        return __METHOD__;
    }

    protected function protectedTraitMethod()
    {
        return __METHOD__;
    }

    private function privateTraitMethod()
    {
        return __METHOD__;
    }

    abstract public function abstractPublicTraitMethod();

    abstract protected function abstractProtectedTraitMethod();
}
