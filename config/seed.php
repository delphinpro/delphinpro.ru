<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023-2024.
 */

return [
    'users_count'    => env('USERS_COUNT', 30),
    'tag_count'      => env('TAGS_COUNT', 10),
    'articles_count' => env('ARTICLES_COUNT', 15),
    'comments_count' => env('COMMENTS_COUNT', 25),

    'links_count'               => env('LINKS_COUNT', 125),
    'link_cats_count'           => env('LINK_CATS_COUNT', 25),
    'links_add_fake_categories' => env('LINKS_FAKE_CATS', true),
    'links_add_fake_links'      => env('LINKS_FAKE_LINKS', true),
];
