<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023-2024.
 */

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * @property-read \Illuminate\Support\Carbon $local_created_at
 * @property-read \Illuminate\Support\Carbon $local_updated_at
 */
trait HasUserTimezoneInTimestamps
{
    public function localCreatedAt(): Attribute
    {
        return new Attribute(
            get: fn() => $this->created_at->toImmutable()->timezone(getUserTimeZone()),
        );
    }

    public function localUpdatedAt(): Attribute
    {
        return new Attribute(
            get: fn() => $this->updated_at->toImmutable()->timezone(getUserTimeZone()),
        );
    }
}
