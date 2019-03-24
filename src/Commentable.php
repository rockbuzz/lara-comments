<?php

namespace Rockbuzz\LaraComments;

trait Commentable
{
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')
            ->whereNull('comment_id');
    }
}
