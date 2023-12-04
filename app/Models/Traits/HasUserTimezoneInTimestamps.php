<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023.
 */

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Carbon;

trait HasUserTimezoneInTimestamps
{
    public function createdAt(): Attribute
    {
        return new Attribute(
            get: fn($value) => Carbon::parse($value)->timezone(getUserTimeZone()),
        );
    }

    public function updatedAt(): Attribute
    {
        return new Attribute(
            get: fn($value) => Carbon::parse($value)->timezone(getUserTimeZone()),
        );
    }
}
