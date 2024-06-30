<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023-2024.
 */

namespace App\Orchid\Screens\User;

use App\Models\User;
use App\Orchid\Screens\Role\Layouts\RolePermissionLayout;
use App\Orchid\Screens\User\Layouts\UserEditLayout;
use App\Orchid\Screens\User\Layouts\UserPasswordLayout;
use App\Orchid\Screens\User\Layouts\UserRoleLayout;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Orchid\Access\Impersonation;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class UserEditScreen extends Screen
{
    public ?User $user = null;

    public function query(User $user): iterable
    {
        $user->load(['roles']);

        return [
            'user'       => $user,
            'permission' => $user->getStatusPermission(),
        ];
    }

    public function name(): ?string
    {
        return $this->user->exists ? 'Edit User' : 'Create User';
    }

    public function description(): ?string
    {
        return 'User profile and privileges, including their associated role.';
    }

    public function permission(): ?iterable
    {
        return [
            'platform.systems.users',
        ];
    }

    public function commandBar(): iterable
    {
        return [
            Button::make(__('Impersonate user'))
                ->icon('bg.box-arrow-in-right')
                ->confirm(__('You can revert to your original state by logging out.'))
                ->method('loginAs')
                ->canSee($this->user->exists && \request()->user()->id !== $this->user->id),

            Button::make(__('Remove'))
                ->icon('bs.trash3')
                ->confirm(__('Once the account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.'))
                ->method('remove')
                ->canSee($this->user->exists),

            Button::make(__('Save'))
                ->icon('bs.check-circle')
                ->method('save'),
        ];
    }

    public function layout(): iterable
    {
        return [

            Layout::block(UserEditLayout::class)
                ->title(__('Profile Information'))
                ->description(__('Update your account\'s profile information and email address.')),

            Layout::block(UserPasswordLayout::class)
                ->title(__('Password'))
                ->description(__('Ensure your account is using a long, random password to stay secure.')),

            Layout::block(UserRoleLayout::class)
                ->title(__('Roles'))
                ->description(__('A Role defines a set of tasks a user assigned the role is allowed to perform.')),

            Layout::block(RolePermissionLayout::class)
                ->title(__('Permissions'))
                ->description(__('Allow the user to perform some actions that are not provided for by his roles')),

        ];
    }

    public function save(User $user, Request $request): RedirectResponse
    {
        $request->validate([
            'user.email' => [
                'required',
                Rule::unique(User::class, 'email')->ignore($user),
            ],
        ]);

        $permissions = collect($request->get('permissions'))
            ->map(fn($value, $key) => [base64_decode($key) => $value])
            ->collapse()
            ->toArray();

        $user->when($request->filled('user.password'), function (Builder $builder) use ($request) {
            $builder->getModel()->password = Hash::make($request->input('user.password'));
        });

        $user
            ->fill($request->collect('user')->except(['password', 'permissions', 'roles'])->toArray())
            ->fill(['permissions' => $permissions])
            ->save();

        $user->replaceRoles($request->input('user.roles'));

        Toast::info(__('User was saved.'));

        return redirect()->route('platform.systems.users');
    }

    /**
     * @throws \Exception
     */
    public function remove(User $user): RedirectResponse
    {
        $user->delete();

        Toast::info(__('User was removed'));

        return redirect()->route('platform.systems.users');
    }

    public function loginAs(User $user): RedirectResponse
    {
        Impersonation::loginAs($user);

        Toast::info(__('You are now impersonating this user'));

        return redirect()->route(config('platform.index'));
    }
}
