<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023-2024.
 */

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * @property-read \Illuminate\Support\Carbon $local_deleted_at
 */
trait HasUserTimezoneInSoftDeletes
{
    public function localDeletedAt(): Attribute
    {
        return new Attribute(
            get: fn() => $this->deleted_at === null ? null
                : $this->deleted_at->toImmutable()->timezone(getUserTimeZone()),
        );
    }
}
