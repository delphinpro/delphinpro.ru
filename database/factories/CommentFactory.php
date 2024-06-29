<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2024.
 */

namespace Database\Factories;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CommentFactory extends Factory
{
    private static ?array $usersIds = null;

    protected $model = Comment::class;

    public function definition(): array
    {
        if (self::$usersIds === null) {
            self::$usersIds = User::select(['id'])->get()->map(fn($u) => $u->id)->push(null)->toArray();
        }

        return [
            'commentable_id'   => 1,
            'commentable_type' => 'article',
            'user_id'          => fake()->randomElement(self::$usersIds),
            'published'        => fake()->randomElement([true, false]),
            'content'          => fake('ru_RU')->realText(),
            'created_at'       => Carbon::now(),
            'updated_at'       => Carbon::now(),
        ];
    }
}
