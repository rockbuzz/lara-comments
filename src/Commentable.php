<?php

namespace Rockbuzz\LaraComments;

trait Commentable
{
    public function comments()
    {
        return $this->morphMany(config('comments.models.comment'), 'commentable')
            ->whereNull('comment_id');
    }
}
