<?php
/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\FakerBundle\Test\Provider;

use Faker\Generator;
use InvalidArgumentException;
use NumberNine\FakerBundle\Provider\Blog;
use PHPUnit\Framework\TestCase;

class TestBlog extends TestCase
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

            $this->assertNotEmpty($title);
            $this->assertNotContains('{{', $title);
            $this->assertNotContains('}}', $title);
            $this->assertEquals(ucwords(strtolower($title)), $title);
            $this->assertEquals(trim($title), $title);
            $this->assertRegExp('/[\d\s\w]+/', $title);
        }
    }

    public function testBlogCategory(): void
    {
        for ($i = 0; $i < 100; $i++) {
            $category = $this->faker->blogCategory;

            $this->assertNotEmpty($category);
            $this->assertEquals(ucwords(strtolower($category)), $category);
            $this->assertEquals(trim($category), $category);
            $this->assertRegExp('/[\w\s]+/', $category);
        }
    }

    public function testBlogFeaturedImage(): void
    {
        for ($i = 0; $i < 20; $i++) {
            $image = $this->faker->blogFeaturedImage;
            $this->assertNotEmpty($image);
            $this->assertFileExists($image);
        }

        for ($i = 0; $i < 100; $i++) {
            $image = $this->faker->blogFeaturedImage($this->faker->blogCategory);
            $this->assertNotEmpty($image);
            $this->assertFileExists($image);
        }

        $this->expectException(InvalidArgumentException::class);
        $this->faker->blogFeaturedImage('Wrong category');
    }
}
