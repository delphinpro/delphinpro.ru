<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023-2024.
 */

namespace App\Orchid\Screens\User\Filters;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Platform\Models\Role;
use Orchid\Screen\Fields\Select;

class RoleFilter extends Filter
{
    public function name(): string
    {
        return __('Roles');
    }

    public function parameters(): array
    {
        return ['role'];
    }

    /** Apply to a given Eloquent query builder. */
    public function run(Builder $builder): Builder
    {
        return $builder->whereHas('roles', function (Builder $query) {
            $query->where('slug', $this->request->get('role'));
        });
    }

    /** Get the display fields. */
    public function display(): array
    {
        return [
            Select::make('role')
                ->fromModel(Role::class, 'name', 'slug')
                ->empty()
                ->value($this->request->get('role'))
                ->title(__('Roles')),
        ];
    }

    /** Value to be displayed */
    public function value(): string
    {
        return $this->name().': '.Role::where('slug', $this->request->get('role'))->first()->name;
    }
}
