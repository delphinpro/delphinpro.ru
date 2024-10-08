<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023-2024.
 */

declare(strict_types=1);

namespace App\Orchid;

use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;
use Orchid\Support\Color;

class PlatformProvider extends OrchidServiceProvider
{
    private array $menuItems = [];

    /**
     * Bootstrap the application services.
     *
     * @param  Dashboard  $dashboard
     *
     * @return void
     */
    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);
    }

    public function addMenu(array $items): void
    {
        foreach ($items as $item) {
            $this->menuItems[] = $item;
        }
    }

    /**
     * Register the application menu.
     *
     * @return Menu[]
     */
    public function menu(): array
    {
        $this->addMenu([
            // Menu::make('Get Started')
            //     ->icon('bs.book')
            //     ->route(config('platform.index')),
            //

            Menu::make('Главная страница')
                ->route('platform.homepage'),

            Menu::make('Публикации')
                ->route('platform.article.list')
                ->title('Контент'),

            Menu::make('Теги')
                ->route('platform.tag.list'),

            Menu::make('Комментарии')
                ->route('platform.comment.list')
                ->divider(),

            Menu::make('Ссылки')
                ->route('platform.link.list'),

            Menu::make('Категории ссылок')
                ->route('platform.link-category.list')
                ->divider(),

            Menu::make(__('Настройки'))
                ->route('platform.settings.general')
                ->active('*/settings/*')
                ->title('Настройки')
                ->divider(),

            Menu::make(__('Пользователи'))
                ->route('platform.systems.users')
                ->permission('platform.systems.users')
                ->title(__('Контроль доступа')),

            Menu::make(__('Роли'))
                ->route('platform.systems.roles')
                ->permission('platform.systems.roles')
                ->divider(),
        ]);

        if (app()->environment('local')) {
            $this->addMenu([
                Menu::make('Примеры экранов админки')
                    ->list([
                        Menu::make('Sample Screen')
                            ->icon('bs.collection')
                            ->route('platform.example')
                            ->badge(fn() => 6),

                        Menu::make('Form Elements')
                            ->icon('bs.card-list')
                            ->route('platform.example.fields')
                            ->active('*/examples/form/*'),

                        Menu::make('Overview Layouts')
                            ->icon('bs.window-sidebar')
                            ->route('platform.example.layouts'),

                        Menu::make('Grid System')
                            ->icon('bs.columns-gap')
                            ->route('platform.example.grid'),

                        Menu::make('Charts')
                            ->icon('bs.bar-chart')
                            ->route('platform.example.charts'),

                        Menu::make('Cards')
                            ->icon('bs.card-text')
                            ->route('platform.example.cards')
                            ->divider(),

                        Menu::make('Онлайн документация')
                            ->icon('bs.box-arrow-up-right')
                            ->url('https://orchid.software/en/docs')
                            ->target('_blank'),

                        Menu::make('Список изменений')
                            ->icon('bs.box-arrow-up-right')
                            ->url('https://github.com/orchidsoftware/platform/blob/master/CHANGELOG.md')
                            ->target('_blank')
                            ->badge(fn() => Dashboard::version(), Color::DARK),
                    ]),
            ]);
        }

        return $this->menuItems;
    }

    /**
     * Register permissions for the application.
     *
     * @return ItemPermission[]
     */
    public function permissions(): array
    {
        return [
            ItemPermission::group(__('System'))
                ->addPermission('platform.systems.roles', __('Roles'))
                ->addPermission('platform.systems.users', __('Users')),
        ];
    }
}
