<?php
/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\FakerBundle\Tests\Provider;

use Faker\Generator;
use InvalidArgumentException;
use NumberNine\FakerBundle\Provider\Blog;
use PHPUnit\Framework\TestCase;

class BlogTest extends TestCase
{
    private Generator $faker;

    public function __construct()
    {
        parent::__construct();
        $this->faker = new Generator();
        $this->faker->addProvider(new Blog($this->faker));
    }

    public function testBlogTitle(): void
    {
        for ($i = 0; $i < 100; $i++) {
            $title = $this->faker->blogTitle;

            self::assertNotEmpty($title);
            self::assertStringNotContainsString('{{', $title);
            self::assertStringNotContainsString('}}', $title);
            self::assertEquals(trim($title), $title);
            self::assertMatchesRegularExpression('/[\d\s\w]+/', $title);
        }
    }

    public function testBlogCategory(): void
    {
        for ($i = 0; $i < 100; $i++) {
            $category = $this->faker->blogCategory;

            self::assertNotEmpty($category);
            self::assertEquals(ucwords(strtolower($category)), $category);
            self::assertEquals(trim($category), $category);
            self::assertMatchesRegularExpression('/[\w\s]+/', $category);
        }
    }

    public function testBlogFeaturedImage(): void
    {
        for ($i = 0; $i < 20; $i++) {
            $image = $this->faker->blogFeaturedImage;
            self::assertNotEmpty($image);
            self::assertFileExists($image);
        }

        for ($i = 0; $i < 100; $i++) {
            $image = $this->faker->blogFeaturedImage($this->faker->blogCategory);
            self::assertNotEmpty($image);
            self::assertFileExists($image);
        }

        $this->expectException(InvalidArgumentException::class);
        $this->faker->blogFeaturedImage('Wrong category');
    }
}
