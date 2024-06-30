<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023-2024.
 */

namespace App\Orchid\Screens\Role;

use App\Orchid\Screens\Role\Layouts\RoleListLayout;
use Orchid\Platform\Models\Role;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class RoleListScreen extends Screen
{
    public function query(): iterable
    {
        return [
            'roles' => Role::filters()->defaultSort('id', 'desc')->paginate(),
        ];
    }

    public function name(): ?string
    {
        return 'Role Management';
    }

    public function description(): ?string
    {
        return 'A comprehensive list of all roles, including their permissions and associated users.';
    }

    public function permission(): ?iterable
    {
        return [
            'platform.systems.roles',
        ];
    }

    public function commandBar(): iterable
    {
        return [
            Link::make(__('Add'))
                ->icon('bs.plus-circle')
                ->href(route('platform.systems.roles.create')),
        ];
    }

    public function layout(): iterable
    {
        return [
            RoleListLayout::class,
        ];
    }
}
