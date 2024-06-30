<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023-2024.
 */

namespace App\Orchid\Screens\User\Layouts;

use Orchid\Screen\Fields\Password;
use Orchid\Screen\Layouts\Rows;

class ProfilePasswordLayout extends Rows
{
    public function fields(): array
    {
        return [
            Password::make('old_password')
                ->placeholder(__('Enter the current password'))
                ->title(__('Current password'))
                ->help('This is your password set at the moment.'),

            Password::make('password')
                ->placeholder(__('Enter the password to be set'))
                ->title(__('New password')),

            Password::make('password_confirmation')
                ->placeholder(__('Enter the password to be set'))
                ->title(__('Confirm new password'))
                ->help('A good password is at least 15 characters or at least 8 characters long, including a number and a lowercase letter.'),
        ];
    }
}
