<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023.
 */

namespace Database\Factories;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

class TagFactory extends Factory
{
    protected $model = Tag::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->word;

        return [
            'name'        => $this->faker->unique()->word,
            'description' => $this->faker->sentences(3, true),
        ];
    }
}
