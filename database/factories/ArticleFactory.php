<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023.
 */

namespace Database\Factories;

use App\Models\Article;
use Database\Seeders\Seed;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleFactory extends Factory
{
    protected $model = Article::class;

    /**
     * @throws \Exception
     * @throws \League\Flysystem\FilesystemException
     */
    public function definition(): array
    {
        $this->faker = \Faker\Factory::create('ru_RU');

        $date = $this->faker->dateTimeBetween('-1 month');

        return [
            'user_id'    => 1,
            'cover_id'   => Seed::loadRandomFile('images'),
            'title'      => ucfirst($this->faker->words($this->faker->numberBetween(3, 7), true)),
            'summary'    => $this->faker->sentences(6, true),
            'content'    => $this->makeContent(),
            'meta'       => [
                'keywords'    => implode(', ', $this->faker->words()),
                'description' => $this->faker->sentences(3, true),
            ],
            'created_at' => $date,
            'updated_at' => $date,
        ];
    }

    private function makeContent(): string
    {
        return implode(
            '',
            array_map(static fn($p) => '<p>'.trim($p).'</p>', $this->faker->paragraphs(30))
        );
    }
}
