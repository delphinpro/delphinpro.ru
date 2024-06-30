<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023-2024.
 */


namespace App\Orchid\Screens\Role\Layouts;

use Orchid\Platform\Models\Role;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Components\Cells\DateTimeSplit;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class RoleListLayout extends Table
{
    public $target = 'roles';

    /**
     * @throws \ReflectionException
     */
    public function columns(): array
    {
        return [
            TD::make('name', __('Name'))
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(fn(Role $role) => Link::make($role->name)
                    ->route('platform.systems.roles.edit', $role->id)),

            TD::make('slug', __('Slug'))
                ->sort()
                ->cantHide()
                ->filter(Input::make()),

            TD::make('created_at', __('Created'))
                ->usingComponent(DateTimeSplit::class)
                ->align(TD::ALIGN_RIGHT)
                ->defaultHidden()
                ->sort(),

            TD::make('updated_at', __('Last edit'))
                ->usingComponent(DateTimeSplit::class)
                ->align(TD::ALIGN_RIGHT)
                ->sort(),
        ];
    }
}
