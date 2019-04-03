<?php

namespace Rockbuzz\LaraComments;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $guarded = [];

    public function commenter()
    {
        return $this->belongsTo(config('comments.models.commenter'));
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

    public function approve()
    {
        return $this->update(['state' => State::APPROVED]);
    }

    public function disapprove()
    {
        return $this->update(['state' => State::DISAPPROVED]);
    }

    public function asPending()
    {
        return $this->update(['state' => State::PENDING]);
    }

    public function scopePending($query, string $commentableType = null): Builder
    {
        $builder = $query->whereState(State::PENDING);

        if ($commentableType) {
            $builder->where('commentable_type', $commentableType);
        }

        return $builder;
    }

    public function scopeApproved($query, string $commentableType = null): Builder
    {
        $builder = $query->whereState(State::APPROVED);

        if ($commentableType) {
            $builder->where('commentable_type', $commentableType);
        }

        return $builder;
    }

    public function scopeDisapproved($query, string $commentableType = null): Builder
    {
        $builder = $query->whereState(State::DISAPPROVED);

        if ($commentableType) {
            $builder->where('commentable_type', $commentableType);
        }

        return $builder;
    }
}
