<?php

namespace Rockbuzz\LaraComments;

use Rockbuzz\LaraUuid\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Rockbuzz\LaraComments\Enums\Status;
use Illuminate\Database\Eloquent\{Builder, SoftDeletes};

class Comment extends Model
{
    use Uuid, SoftDeletes;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'title',
        'body',
        'likes',
        'type',
        'status',
        'comment_id',
        'commentable_id',
        'commentable_type',
        'commenter_id',
        'commenter_type'
    ];

    protected $casts = [
        'id' => 'string',
        'likes' => 'integer',
        'type' => 'integer',
        'status' => 'integer'
    ];

    protected $dates = [
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('comments.tables.comments'));
    }


    public function commenter()
    {
        return $this->morphTo(config('comments.tables.morph_names.commenter'));
    }

    public function commentable()
    {
        return $this->morphTo(config('comments.tables.morph_names.commentable'));
    }

    public function children()
    {
        return $this->hasMany(Comment::class, 'comment_id');
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'comment_id');
    }

    public function isPending(): bool
    {
        return $this->status === Status::PENDING;
    }

    public function isApproved(): bool
    {
        return $this->status === Status::APPROVED;
    }

    public function isDisapproved(): bool
    {
        return $this->status === Status::DISAPPROVED;
    }

    public function scopePending($query): Builder
    {
        return $query->whereStatus(Status::PENDING);
    }

    public function scopeApproved($query): Builder
    {
        return $query->whereStatus(Status::APPROVED);
    }

    public function scopeDisapproved($query): Builder
    {
        return $query->whereStatus(Status::DISAPPROVED);
    }
}
