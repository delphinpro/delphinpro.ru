<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2024.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Orchid\Screen\AsSource;

class LinkCategory extends Model
{
    use HasFactory;
    use AsSource;

    public $timestamps = false;

    protected $fillable = [
        'title',
    ];

    public function links(): BelongsToMany
    {
        return $this->belongsToMany(Link::class);
    }
}
