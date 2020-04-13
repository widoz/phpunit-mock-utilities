<?php

declare(strict_types=1);

namespace Widoz\PhpUnit\Mock\Utilities;

use Faker\Factory;
use Faker\Generator as FakerGenerator;
use InvalidArgumentException;

/**
 * Fake common data values
 */
final class Faker
{
    /**
     * Create Faker instance
     *
     * @throws InvalidArgumentException
     */
    public static function faker(): FakerGenerator
    {
        static $faker = null;

        if ($faker === null) {
            $faker = self::createFaker();
        }

        return $faker;
    }

    /**
     * Create an instance of a FakerGenerator
     *
     * @throws InvalidArgumentException
     * @return FakerGenerator
     */
    private static function createFaker(): FakerGenerator
    {
        $fakeFactory = new Factory();

        return $fakeFactory->create();
    }
}
