<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023-2024.
 */

namespace App\Orchid\Screens\User\Layouts;

use Orchid\Platform\Models\User;
use Orchid\Screen\Fields\Password;
use Orchid\Screen\Layouts\Rows;

class UserPasswordLayout extends Rows
{
    public function fields(): array
    {
        /** @var User $user */
        $user = $this->query->get('user');

        $placeholder = $user->exists
            ? __('Leave empty to keep current password')
            : __('Enter the password to be set');

        return [
            Password::make('user.password')
                ->placeholder($placeholder)
                ->title(__('Password')),
        ];
    }
}
