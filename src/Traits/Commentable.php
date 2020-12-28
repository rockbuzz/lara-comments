<?php

declare(strict_types=1);

namespace Rockbuzz\LaraComments\Traits;

use Rockbuzz\LaraComments\Enums\Status;
use Rockbuzz\LaraComments\Models\Comment;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Rockbuzz\LaraComments\Events\{ApprovedEvent, AsPendingEvent, UnapprovedEvent};

trait Commentable
{
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')
            ->whereNull('parent_id');
    }

    public function hasComments(): bool
    {
        return $this->comments()->exists();
    }

    /**
     * @var Comment|int $comment
     */
    public function asPending($comment): void
    {
        $comment = $this->commentResolve($comment);

        $comment->update(['status' => Status::PENDING]);

        event(new AsPendingEvent($comment));
    }

    /**
     * @var Comment|int $comment
     */
    public function approve($comment): void
    {
        $comment = $this->commentResolve($comment);

        $comment->update(['status' => Status::APPROVED]);

        event(new ApprovedEvent($comment));
    }

    /**
     * @var Comment|int $comment     */
    public function unapprove($comment): void
    {
        $comment = $this->commentResolve($comment);

        $comment->update(['status' => Status::UNAPPROVED]);

        event(new UnapprovedEvent($comment));
    }

    /**
     * @param $comment
     * @return Comment
     */
    private function commentResolve($comment): Comment
    {
        return is_a($comment, Comment::class) ? $comment : Comment::findOrFail($comment);
    }
}
