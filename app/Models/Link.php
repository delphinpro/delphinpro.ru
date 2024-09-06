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

class Link extends Model
{
    use HasFactory;
    use AsSource;

    public $timestamps = false;

    protected $fillable = [
        'title',
        'url',
        'cover',
        'background',
        'published',
    ];

    protected $casts = [
        'published' => 'boolean',
    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(LinkCategory::class);
    }
}
