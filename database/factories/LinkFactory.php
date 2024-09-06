<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2024.
 */

namespace Database\Factories;

use App\Models\Link;
use Database\Seeders\Seed;
use Illuminate\Database\Eloquent\Factories\Factory;
use RuntimeException;

class LinkFactory extends Factory
{
    protected $model = Link::class;

    public function definition(): array
    {
        $dir = storage_path('app/public/links');

        if (!is_dir($dir) && !mkdir($dir) && !is_dir($dir)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $dir));
        }

        return [
            'title'      => ucfirst($this->faker->unique()->words(2, true)),
            'url'        => $this->faker->url(),
            'cover'      => Seed::copyRandomPublicFile('links', 'links'),
            'background' => $this->faker->hexColor(),
            'published'  => $this->faker->boolean(90),
        ];
    }
}
