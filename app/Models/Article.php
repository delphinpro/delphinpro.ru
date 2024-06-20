<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023-2024.
 */

namespace App\Models;

use App\Models\Traits\HasUserTimezoneInSoftDeletes;
use App\Models\Traits\HasUserTimezoneInTimestamps;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Orchid\Attachment\Attachable;
use Orchid\Attachment\Models\Attachment;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Article extends Model
{
    use HasFactory;
    use SoftDeletes;

    use HasUserTimezoneInTimestamps;
    use HasUserTimezoneInSoftDeletes;

    use AsSource;
    use Filterable;
    use Attachable;

    public const PER_PAGE = 10;

    protected $fillable = [
        'user_id',
        'cover_id',
        'published',
        'title',
        'summary',
        'content',
        'meta',
        'keywords',
        'description',
    ];

    protected $casts = [
        'user_id'   => 'integer',
        'cover_id'  => 'integer',
        'published' => 'boolean',
        'meta'      => 'array',
    ];

    protected array $allowedSorts = [
        'title',
        'published',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function keywords(): Attribute
    {
        return new Attribute(
            get: fn() => $this->meta['keywords'] ?? '',
            set: function (mixed $value) {
                $meta = $this->meta ?? [];
                $meta['keywords'] = $value;

                return ['meta' => json_encode($meta, JSON_THROW_ON_ERROR)];
            },
        );
    }

    public function description(): Attribute
    {
        return new Attribute(
            get: fn() => $this->meta['description'] ?? '',
            set: function (mixed $value) {
                $meta = $this->meta ?? [];
                $meta['description'] = $value;

                return ['meta' => json_encode($meta, JSON_THROW_ON_ERROR)];
            },
        );
    }

    public function cover(): HasOne
    {
        return $this->hasOne(Attachment::class, 'id', 'cover_id')->withDefault();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function scopeLastPublished(Builder $query): void
    {
        $query->where('published', true)->orderByDesc('created_at')->orderByDesc('id');
    }
}
