<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\FakerBundle\Generator;

use Faker\Factory;
use Faker\Generator;
use NumberNine\FakerBundle\Provider\Blog;

class FakerFactory
{
    public static function create(): Generator
    {
        $faker = Factory::create();
        $faker->addProvider(new Blog($faker));

        return $faker;
    }
}
