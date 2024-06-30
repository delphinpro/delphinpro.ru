<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023-2024.
 */

namespace App\Orchid\Screens\User\Layouts;

use Orchid\Platform\Models\Role;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class UserRoleLayout extends Rows
{
    public function fields(): array
    {
        return [
            Select::make('user.roles.')
                ->fromModel(Role::class, 'name')
                ->multiple()
                ->title(__('Name role'))
                ->help('Specify which groups this account should belong to'),
        ];
    }
}
