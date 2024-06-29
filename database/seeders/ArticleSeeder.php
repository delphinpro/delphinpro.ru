<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023-2024.
 */

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Comment;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * @throws \League\Flysystem\FilesystemException
     */
    public function run(): void
    {
        Article::factory()
            ->count(config('seed.articles_count', 15))
            ->create();

        $tags = Tag::all();
        $commentsCount = config('seed.comments_count', 25);

        Article::all()->each(static function (Article $article) use ($tags, $commentsCount) {
            $article->tags()->attach(
                $tags->random(5)->pluck('id')
            );

            if ($commentsCount) {
                $article->comments()->createMany(
                    Comment::factory()->count(mt_rand(0, $commentsCount))->state(new Sequence(
                        fn(Sequence $sequence) => [
                            'created_at' => $date = fake()->dateTimeBetween($article->created_at),
                            'updated_at' => $date,
                        ],
                    ))->make()->toArray()
                );
            }
        });
    }
}
