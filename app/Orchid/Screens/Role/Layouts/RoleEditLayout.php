<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023-2024.
 */

namespace App\Orchid\Screens\Role\Layouts;

use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;

class RoleEditLayout extends Rows
{
    public function fields(): array
    {
        return [
            Input::make('role.name')
                ->type('text')
                ->max(255)
                ->required()
                ->title(__('Name'))
                ->placeholder(__('Name'))
                ->help(__('Role display name')),

            Input::make('role.slug')
                ->type('text')
                ->max(255)
                ->required()
                ->title(__('Slug'))
                ->placeholder(__('Slug'))
                ->help(__('Actual name in the system')),
        ];
    }
}
