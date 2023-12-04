<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023.
 */

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Carbon;

trait HasUserTimezoneInSoftDeletes
{
    public function deletedAt(): Attribute
    {
        return new Attribute(
            get: fn($value) => $value === null ? null : Carbon::parse($value)->timezone(getUserTimeZone()),
        );
    }
}
