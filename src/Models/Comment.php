<?php

namespace Rockbuzz\LaraComments\Models;

use Rockbuzz\LaraComments\Enums\Status;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\{Model, Builder, SoftDeletes};
use Illuminate\Database\Eloquent\Relations\{HasMany, MorphTo};

class Comment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'body',
        'type',
        'status',
        'user_id',
        'parent_id',
        'commentable_id',
        'commentable_type'
    ];

    protected $casts = [
        'id' => 'int',
        'type' => 'integer',
        'status' => 'integer'
    ];

    protected $dates = [
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    public function commenter(): BelongsTo
    {
        return $this->belongsTo(config('comments.models.commenter'), 'user_id');
    }

    public function commentable(): MorphTo
    {
        return $this->morphTo('commentable');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function isPending(): bool
    {
        return $this->status === Status::PENDING;
    }

    public function isApproved(): bool
    {
        return $this->status === Status::APPROVED;
    }

    public function isUnapproved(): bool
    {
        return $this->status === Status::UNAPPROVED;
    }

    public function scopePending($query): Builder
    {
        return $query->whereStatus(Status::PENDING);
    }

    public function scopeApproved($query): Builder
    {
        return $query->whereStatus(Status::APPROVED);
    }

    public function scopeUnapproved($query): Builder
    {
        return $query->whereStatus(Status::UNAPPROVED);
    }
}
