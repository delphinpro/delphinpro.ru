<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2019-2024.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
        'permissions',
        'settings',
    ];

    protected $casts = [
        'permissions'       => 'array',
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'settings'          => AsArrayObject::class,
    ];

    public function isAdmin(): bool
    {
        return $this->inRole('admin');
    }
}
