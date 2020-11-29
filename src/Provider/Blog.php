<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\FakerBundle\Provider;

use Faker\Generator;
use Faker\Provider\Base;
use InvalidArgumentException;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Yaml\Yaml;

class Blog extends Base
{
    private array $nouns;
    private array $verbs;
    private array $adverbs;
    private array $adjectives;
    private array $titles;
    private array $categories = ['Art', 'Food', 'Lifestyle', 'Movie', 'Music', 'Travel'];
    private array $images = [];

    public function __construct(Generator $generator)
    {
        parent::__construct($generator);

        $fakerResourcesRoot = dirname(__DIR__) . '/Resources/faker/';

        $this->nouns = Yaml::parseFile($fakerResourcesRoot . 'words/nouns.yaml')['nouns'];
        $this->verbs = Yaml::parseFile($fakerResourcesRoot . 'words/verbs.yaml')['verbs'];
        $this->adverbs = Yaml::parseFile($fakerResourcesRoot . 'words/adverbs.yaml')['adverbs'];
        $this->adjectives = Yaml::parseFile($fakerResourcesRoot . 'words/adjectives.yaml')['adjectives'];
        $this->titles = Yaml::parseFile($fakerResourcesRoot . 'templates/titles.yaml')['titles'];

        $finder = new Finder();
        $finder->in($fakerResourcesRoot . 'images/')->directories();
        $directories = array_map(fn(SplFileInfo $file) => $file->getBasename(), iterator_to_array($finder));

        foreach ($directories as $path => $name) {
            $finder = new Finder();
            $finder->in($path)->files();

            $this->images[$name] = array_values(
                array_map(
                    fn(SplFileInfo $file) => $file->getRealPath(),
                    iterator_to_array($finder)
                )
            );
        }
    }

    public function blogTitle(): string
    {
        return ucwords($this->replacePlaceholders(static::randomElement($this->titles)));
    }

    public function blogCategory(): string
    {
        return static::randomElement($this->categories);
    }

    public function blogFeaturedImage(string $category = null): string
    {
        if ($category && !array_key_exists($category, $this->images)) {
            throw new InvalidArgumentException(
                sprintf(
                    "Specified category '%s' must be one of: %s",
                    $category,
                    implode(', ', array_keys($this->images))
                )
            );
        }

        return $category
            ? static::randomElement($this->images[$category])
            : static::randomElement($this->images[static::randomElement(array_keys($this->images))]);
    }

    private function replacePlaceholders(string $text): string
    {
        $search = [
            '{{ number }}',
            '{{ noun }}',
            '{{ verb }}',
            '{{ adjective }}',
            '{{ adverb }}',
        ];

        $replace = [
            random_int(5, 20),
            static::randomElement($this->nouns),
            static::randomElement($this->verbs),
            static::randomElement($this->adjectives),
            static::randomElement($this->adverbs),
        ];

        return str_replace($search, $replace, $text);
    }
}
