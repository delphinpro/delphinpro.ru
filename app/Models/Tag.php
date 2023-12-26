<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Tag extends Model
{
    use HasFactory;

    use AsSource;
    use Filterable;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
    ];

    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class);
    }
}
