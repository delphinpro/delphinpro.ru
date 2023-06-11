<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023.
 */

declare(strict_types=1);

namespace App\Orchid;

use Nwidart\Modules\Facades\Module;
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

            Menu::make(__('Пользователи'))
                ->icon('bs.people')
                ->route('platform.systems.users')
                ->permission('platform.systems.users')
                ->title(__('Контроль доступа')),

            Menu::make(__('Роли'))
                ->icon('bs.lock')
                ->route('platform.systems.roles')
                ->permission('platform.systems.roles')
                ->divider(),
        ]);

        if (Module::find('OrchidExamples')?->isEnabled()) {
            $this->addMenu([
                Menu::make('Примеры экранов админки')
                    ->icon('code')
                    ->list([
                        Menu::make('Пример экрана')
                            ->icon('bs.collection')
                            ->route('platform.example')
                            ->badge(fn() => 6),

                        Menu::make('Формы')
                            ->icon('bs.journal')
                            ->route('platform.example.fields')
                            ->active('*/form/*'),

                        Menu::make('Раскладки')
                            ->icon('bs.columns-gap')
                            ->route('platform.example.layouts')
                            ->active('*/layouts/*'),

                        Menu::make('Графики')
                            ->icon('bs.bar-chart')
                            ->route('platform.example.charts'),

                        Menu::make('Карточки')
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
