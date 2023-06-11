<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Variable extends Model
{
    public $timestamps = false;

    protected $primaryKey = 'name';

    protected $fillable = [
        'name',
        'value',
    ];

    protected $casts = [
        'value' => 'array',
    ];

    public function val(): Attribute
    {
        return new Attribute(
            get: fn() => $this->value,
            set: fn($v) => $this->value = $v
        );
    }
}
