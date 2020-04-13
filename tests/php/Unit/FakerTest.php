<?php

declare(strict_types=1);

namespace Widoz\PhpUnit\Mock\Utilities\Tests\Unit;

use Faker\Factory;
use PHPUnit\Framework\TestCase;
use Widoz\PhpUnit\Mock\Utilities\Faker;

class FakerTest extends TestCase
{
    /**
     * Test Faker factory
     */
    public function testFakerFactory(): void
    {
        $faker = Faker::faker();

        parent::assertEquals(
            (new Factory())->create(),
            $faker
        );
    }

    /**
     * Test the faker instance is created only once
     */
    public function testFakerIsCreatedOnlyOnce(): void
    {
        $faker = Faker::faker();
        $sameFakerInstance = Faker::faker();

        parent::assertSame($sameFakerInstance, $faker);
    }
}
