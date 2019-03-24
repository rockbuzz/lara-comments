<?php

namespace Rockbuzz\LaraComments;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $guarded = [];

    public function commenter()
    {
        return $this->belongsTo(config('comments.commenter'));
    }

    public function commentable()
    {
        return $this->morphTo('commentable');
    }

    public function children()
    {
        return $this->hasMany(Comment::class, 'comment_id');
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'comment_id');
    }

    public function scopePending($query): Builder
    {
        return $query->whereStatus(State::PENDING);
    }

    public function scopeApproved($query): Builder
    {
        return $query->whereStatus(State::APPROVED);
    }

    public function scopeUnapproved($query): Builder
    {
        return $query->whereStatus(State::UNAPPROVED);
    }
}
