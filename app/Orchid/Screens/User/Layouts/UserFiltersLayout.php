<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023-2024.
 */

namespace App\Orchid\Screens\User\Layouts;

use App\Orchid\Screens\User\Filters\RoleFilter;
use Orchid\Screen\Layouts\Selection;

class UserFiltersLayout extends Selection
{
    public function filters(): array
    {
        return [
            RoleFilter::class,
        ];
    }
}
