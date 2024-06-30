<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2024.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Orchid\Screen\AsSource;
use Parsedown;

class Comment extends Model
{
    use HasFactory;
    use SoftDeletes;
    use AsSource;

    protected $fillable = [
        'commentable_id',
        'commentable_type',
        'user_id',
        'published',
        'content',
    ];

    protected $casts = [
        'commentable_id' => 'integer',
        'user_id'        => 'integer',
        'published'      => 'boolean',
    ];

    /*==
     *== Relationships
     *== ======================================= ==*/

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    /*==
     *== Helpers
     *== ======================================= ==*/

    public function parsed(): string
    {
        $parser = Parsedown::instance();
        $parser->setSafeMode(true);

        $html = $parser->parse($this->content);

        return safeHtmlString($html);
    }
}
