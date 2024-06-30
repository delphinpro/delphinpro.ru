<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023-2024.
 */

namespace App\Orchid\Screens\Role;

use App\Orchid\Screens\Role\Layouts\RoleEditLayout;
use App\Orchid\Screens\Role\Layouts\RolePermissionLayout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Orchid\Platform\Models\Role;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class RoleEditScreen extends Screen
{
    public ?Role $role = null;

    public function query(Role $role): iterable
    {
        return [
            'role'       => $role,
            'permission' => $role->getStatusPermission(),
        ];
    }

    public function name(): ?string
    {
        return 'Edit Role';
    }

    public function description(): ?string
    {
        return 'Modify the privileges and permissions associated with a specific role.';
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
            Button::make(__('Save'))
                ->icon('bs.check-circle')
                ->method('save'),

            Button::make(__('Remove'))
                ->icon('bs.trash3')
                ->method('remove')
                ->canSee($this->role->exists),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::block([
                RoleEditLayout::class,
            ])
                ->title('Role')
                ->description('A role is a collection of privileges (of possibly different services like the Users service, Moderator, and so on) that grants users with that role the ability to perform certain tasks or operations.'),

            Layout::block([
                RolePermissionLayout::class,
            ])
                ->title('Permission/Privilege')
                ->description('A privilege is necessary to perform certain tasks and operations in an area.'),
        ];
    }

    public function save(Request $request, Role $role): RedirectResponse
    {
        $request->validate([
            'role.slug' => [
                'required',
                Rule::unique(Role::class, 'slug')->ignore($role),
            ],
        ]);

        $role->fill($request->get('role'));

        $role->permissions = collect($request->get('permissions'))
            ->map(fn($value, $key) => [base64_decode($key) => $value])
            ->collapse()
            ->toArray();

        $role->save();

        Toast::info(__('Role was saved'));

        return redirect()->route('platform.systems.roles');
    }

    /**
     * @throws \Exception
     */
    public function remove(Role $role): RedirectResponse
    {
        $role->delete();

        Toast::info(__('Role was removed'));

        return redirect()->route('platform.systems.roles');
    }
}
