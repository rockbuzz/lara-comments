<?php

declare(strict_types=1);

namespace Rockbuzz\LaraComments\Traits;

use Illuminate\Database\Eloquent\Model;
use Rockbuzz\LaraComments\Models\Comment;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait Commenter
{
    public function comments(): HasMany
    {
        return $this->hasMany(config('comments.models.comment'));
    }

    public function commentOn(Model $commentable, string $body, string $title = null): Comment
    {
        return $this->comments()->create([
            'title' => $title,
            'body' => $body,
            'commentable_id' => $commentable->id,
            'commentable_type' => get_class($commentable)
        ]);
    }
}
