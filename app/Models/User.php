<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2019-2024.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Orchid\Platform\Models\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'trust_level',
        'permissions',
        'settings',
    ];

    protected $casts = [
        'permissions'       => 'array',
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'trust_level'       => 'integer',
        'settings'          => AsArrayObject::class,
    ];

    /*==
     *== Relationships
     *== ======================================= ==*/

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /*==
     *== Helpers
     *== ======================================= ==*/

    public function isAdmin(): bool
    {
        return $this->inRole('admin');
    }

    public function allowCommentWithoutModerate(): bool
    {
        return $this->trust_level >= 20 || $this->isAdmin();
    }

    public function trustLevelUp(int $value = 1): void
    {
        if ($this->trust_level < 2147483647) {
            $this->trust_level += $value;
            $this->save();
        }
    }

    public function trustLevelDown(int $value = 1): void
    {
        if ($this->trust_level > -2147483647) {
            $this->trust_level -= $value;
            $this->save();
        }
    }
}
