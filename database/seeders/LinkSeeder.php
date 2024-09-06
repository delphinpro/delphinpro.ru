<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2024.
 */

namespace Database\Seeders;

use App\Models\Link;
use App\Models\LinkCategory;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class LinkSeeder extends Seeder
{
    private static array $cats = [
        'Admin panel',
        'CSS framework',
        'Datepicker',
        'Laravel',
        'Frontend builder',
        'Icon set',
        'Javascript framework',
        'Javascript slider',
        'PHP framework',
        'PHP library',
        'Generator',
    ];

    private static array $links = [
        'Webpack',
        'Bootstrap 5',
        'Swiper API',
        'Stimulus RU',
        'PHP Faker',
        'Laravel Docs EN',
        'Laravel 8.x RU',
        'Orchid',
        'Laravel Mix',
        'Livewire',
        'Рыба текст',
        'Font Awesome v6',
        'Air Datepicker',
    ];

    public function run(): void
    {
        $cats = LinkCategory::factory(count(self::$cats))
            ->sequence(fn(Sequence $seq) => ['title' => self::$cats[$seq->index]])
            ->create();

        Link::factory(count(self::$links))
            ->sequence(fn(Sequence $seq) => ['title' => self::$links[$seq->index]])
            ->create()
            ->each(fn(Link $link) => $link->categories()->attach($cats->random(mt_rand(1, 4))->pluck('id')));

        $cats->push(
            ... LinkCategory::factory(max(config('seed.link_cats_count') - count(self::$cats), 0))->create()
        );

        Link::factory(max(config('seed.links_count') - count(self::$links), 0))
            ->create()
            ->each(fn($link) => $link->categories()->attach($cats->random(mt_rand(1, 4))->pluck('id')));
    }
}
