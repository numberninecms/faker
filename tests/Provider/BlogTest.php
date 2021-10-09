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
use Symfony\Component\Yaml\Yaml;

class BlogTest extends TestCase
{
    private Generator $faker;
    private array $titles;
    private array $categories = ['Art', 'Food', 'Lifestyle', 'Movie', 'Music', 'Travel'];

    public function __construct()
    {
        parent::__construct();
        $fakerResourcesRoot = __DIR__ . '/../../src/Resources/faker/';
        $this->titles = Yaml::parseFile($fakerResourcesRoot . 'templates/titles.yaml')['titles'];
        $this->faker = new Generator();
        $this->faker->addProvider(new Blog($this->faker));
    }

    public function testBlogTitle(): void
    {
        $title = $this->faker->blogTitle;

        static::assertNotEmpty($title);
        static::assertEquals(trim($title), $title);
        static::assertContains($title, array_merge(...array_values($this->titles)));
    }

    public function testBlogCategoryTitle(): void
    {
        $title = $this->faker->blogTitle('Movie');

        static::assertNotEmpty($title);
        static::assertEquals(trim($title), $title);
        static::assertContains($title, $this->titles['Movie']);
    }

    public function testBlogCategory(): void
    {
        $category = $this->faker->blogCategory;

        static::assertNotEmpty($category);
        static::assertEquals(ucwords(strtolower($category)), $category);
        static::assertEquals(trim($category), $category);
        static::assertContains($category, $this->categories);
    }

    public function testBlogFeaturedImage(): void
    {
        for ($i = 0; $i < 20; $i++) {
            $image = $this->faker->blogFeaturedImage;
            static::assertNotEmpty($image);
            static::assertFileExists($image);
        }

        for ($i = 0; $i < 100; $i++) {
            $image = $this->faker->blogFeaturedImage($this->faker->blogCategory);
            static::assertNotEmpty($image);
            static::assertFileExists($image);
        }

        $this->expectException(InvalidArgumentException::class);
        $this->faker->blogFeaturedImage('Wrong category');
    }
}
