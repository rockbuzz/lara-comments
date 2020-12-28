<?php

declare(strict_types=1);

namespace Rockbuzz\LaraComments\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Rockbuzz\LaraComments\Models\Comment;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait Commenter
{
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
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

    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(Comment::class, 'likes')
            ->withTimestamps()
            ->as('likes');
    }

    public function likeTo(Comment $comment): void
    {
        $this->likes()->attach($comment);
    }

    public function dislikeTo(Comment $comment): void
    {
        $this->likes()->detach($comment);
    }
}
