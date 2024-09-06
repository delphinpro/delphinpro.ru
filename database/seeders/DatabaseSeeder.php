<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2019-2024.
 */

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(UserSeeder::class);
        $this->call(HomepageSeeder::class);
        $this->call(TagSeeder::class);
        $this->call(ArticleSeeder::class);
        $this->call(LinkSeeder::class);
    }
}
