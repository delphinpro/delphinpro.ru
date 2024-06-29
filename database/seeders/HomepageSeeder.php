<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023-2024.
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HomepageSeeder extends Seeder
{
    /**
     * @throws \League\Flysystem\FilesystemException
     * @throws \JsonException
     */
    public function run(): void
    {
        DB::table('variables')->insert([
            [
                'name'  => 'intro',
                'value' => json_encode([
                    'enabled'    => true,
                    'title'      => 'Web-development',
                    'subtitle'   => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit',
                    'background' => Seed::loadFile('content/homepage/business-computer.jpg')->id,
                ], JSON_THROW_ON_ERROR),
            ],
            [
                'name'  => 'aboutMe',
                'value' => json_encode([
                    'enabled'    => true,
                    'strip'      => true,
                    'title'      => 'О нас',
                    'content'    => '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Distinctio expedita voluptate voluptatem. Amet consequuntur corporis earum enim, et mollitia nm nemo, neque, obcaecati officia quidem ratione recusandae reiciendis rem voluptatum.</p> <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Distinctio expedita voluptate voluptatem. Amet consequuntur corporis earum enim, et mollitia nam nemo, neque, obcaecati officia quidem ratione recusandae reiciendis rem voluptatum.</p> <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Distinctio expedita voluptate voluptatem. Amet consequuntur corporis earum enim, et mollitia nam nemo, neque, obcaecati officia quidem ratione recusandae reiciendis rem voluptatum.</p>',
                    'skills'     => 'php, css, html, javascript, laravel, e-commerce, git, mysql, vue, react, sass, evolution cms',
                    'background' => Seed::loadFile('content/homepage/about-me-cover.jpg')->id,
                ], JSON_THROW_ON_ERROR),
            ],
            [
                'name'  => 'lastArticles',
                'value' => json_encode([
                    'enabled'  => true,
                    'title'    => 'Статьи и заметки',
                    'subtitle' => 'Последние опубликованные материалы',
                    'count'    => 3,
                ], JSON_THROW_ON_ERROR),
            ],
        ]);
    }
}
