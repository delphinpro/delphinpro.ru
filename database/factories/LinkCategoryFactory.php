<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2024.
 */

namespace Database\Factories;

use App\Models\LinkCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class LinkCategoryFactory extends Factory
{
    protected $model = LinkCategory::class;

    public function definition(): array
    {
        return [
            'title' => ucfirst($this->faker->unique()->words(2, true)),
        ];
    }
}
