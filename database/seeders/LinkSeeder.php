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

const   C_Frontend_Builder = 1;
const   C_CSS_Framework = 2;
const   C_Javascript_Framework = 3;
const   C_Javascript = 4;
const   C_Slider = 5;
const   C_PHP_Library = 6;
const   C_PHP_Framework = 7;
const   C_Laravel = 8;
const   C_Admin_Panel = 9;
const   C_Generator = 10;
const   C_Icons = 11;
const   C_Datepicker = 12;
const   C_Forms = 13;
const   C_CSS = 14;
const   C_PHP = 15;
const   C_TALL_Stack = 16;

class LinkSeeder extends Seeder
{
    private static array $cats = [
        C_Frontend_Builder     => 'Frontend Builder',
        C_CSS_Framework        => 'CSS Framework',
        C_Javascript_Framework => 'Javascript Framework',
        C_Javascript           => 'Javascript',
        C_Slider               => 'Slider',
        C_PHP_Library          => 'PHP Library',
        C_PHP_Framework        => 'PHP Framework',
        C_Laravel              => 'Laravel',
        C_Admin_Panel          => 'Admin Panel',
        C_Generator            => 'Generator',
        C_Icons                => 'Icons',
        C_Datepicker           => 'Datepicker',
        C_Forms                => 'Forms',
        C_CSS                  => 'CSS',
        C_PHP                  => 'PHP',
        C_TALL_Stack           => 'TALL Stack',
    ];

    public function run(): void
    {
        $cats = LinkCategory::factory(count(self::$cats))
            ->sequence(fn(Sequence $seq) => ['title' => self::$cats[$seq->index + 1]])
            ->create();

        $links = $this->getLinks();

        foreach ($links as $link) {
            $cover = array_key_exists('cover', $link)
                ? Seed::copyPublicFile("links/{$link['cover']}", 'links')
                : null;

            Link::create([
                'title'      => $link['title'],
                'url'        => $link['url'],
                'cover'      => $cover,
                'background' => $link['background'] ?? null,
                'published'  => true,
            ])->categories()->attach($link['cats'] ?? []);
        }

        if (config('seed.links_add_fake_categories')) {
            $cats->push(
                ... LinkCategory::factory(max(config('seed.link_cats_count') - count(self::$cats), 0))->create()
            );
        }

        if (config('seed.links_add_fake_links')) {
            Link::factory(max(config('seed.links_count') - count($links), 0))
                ->create()
                ->each(fn($link) => $link->categories()->attach($cats->random(mt_rand(1, 4))->pluck('id')));
        }
    }

    private function getLinks(): array
    {
        return [
            [
                'title'      => 'Webpack',
                'url'        => 'https://webpack.js.org/concepts/',
                'cover'      => 'webpack.svg',
                'background' => '#2B3A42',
                'cats'       => [C_Frontend_Builder],
            ],
            [
                'title' => 'Bootstrap 5',
                'url'   => 'https://getbootstrap.com/docs/5.2',
                'cover' => 'bootstrap-logo.svg',
                'cats'  => [C_CSS, C_CSS_Framework],
            ],
            [
                'title' => 'Swiper API',
                'url'   => 'https://swiperjs.com/swiper-api',
                'cover' => 'swiper-logo.svg',
                'cats'  => [C_Javascript, C_Slider],
            ],
            [
                'title' => 'Stimulus RU',
                'url'   => 'https://delphinpro.github.io/stimulus-doc-ru/',
                'cover' => 'stimulus.svg',
                'cats'  => [C_Javascript, C_Javascript_Framework],
            ],
            [
                'title' => 'PHP Faker',
                'url'   => 'https://fakerphp.github.io/',
                'cover' => 'phpfaker.svg',
                'cats'  => [C_PHP, C_PHP_Library],
            ],
            [
                'title' => 'Laravel Docs',
                'url'   => 'https://laravel.com/docs',
                'cover' => 'laravel.svg',
                'cats'  => [C_PHP, C_PHP_Framework, C_Laravel],
            ],
            [
                'title'      => 'Orchid Platform',
                'url'        => 'https://orchid.software/ru/docs/',
                'cover'      => 'orchid.svg',
                'background' => '#363343',
                'cats'       => [C_PHP, C_Laravel, C_Admin_Panel],
            ],
            [
                'title' => 'Laravel Mix',
                'url'   => 'https://laravel-mix.com/docs',
                'cover' => 'laravel-mix.svg',
                'cats'  => [C_Frontend_Builder, C_Laravel],
            ],
            [
                'title' => 'Livewire v2',
                'url'   => 'https://laravel-livewire.com/docs',
                'cover' => 'livewire.svg',
                'cats'  => [C_Laravel, C_TALL_Stack],
            ],
            [
                'title'      => 'Рыба текст',
                'url'        => 'https://fish-text.ru/',
                'cover'      => 'fish-text.svg',
                'background' => '#E2E8F0',
                'cats'       => [C_Generator],
            ],
            [
                'title' => 'Font Awesome v6',
                'url'   => 'https://fontawesome.com/v6/search',
                'cover' => 'font-awesome.svg',
                'cats'  => [C_Icons],
            ],
            [
                'title' => 'Air Datepiker',
                'desc'  => 'Datepiker',
                'url'   => 'https://air-datepicker.com/ru',
                'cats'  => [C_Javascript, C_Datepicker, C_Forms],
            ],
            [
                'title' => 'Livewire v3',
                'url'   => 'https://livewire.laravel.com/docs',
                'cover' => 'livewire.svg',
                'cats'  => [C_Laravel, C_TALL_Stack],
            ],
        ];
    }
}
