<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023-2024.
 */

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        Tag::create(['name' => 'laravel']);   // 1
        Tag::create(['name' => 'orchid']);    // 2
        Tag::create(['name' => 'tinymce']);   // 3
        Tag::create(['name' => 'git']);       // 4
        Tag::create(['name' => 'css']);       // 5
        Tag::create(['name' => 'loader']);    // 6
        Tag::create(['name' => 'верстка']);   // 7

        Tag::factory(config('seed.tag_count', 50))
            ->create();
    }
}
