<?php

namespace Rockbuzz\LaraComments\Traits;

use Rockbuzz\LaraComments\Enums\Status;
use Rockbuzz\LaraComments\Models\Comment;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Rockbuzz\LaraComments\Events\{ApprovedEvent, AsPendingEvent, DisapprovedEvent};

trait Commentable
{
    public function comments(): MorphMany
    {
        return $this->morphMany(
            config('comments.models.comment'),
            config('comments.tables.morph_names.commentable')
        )->whereNull('comment_id');
    }

    public function asPending($comment): void
    {
        $comment = $this->getComment($comment);

        $comment->update(['status' => Status::PENDING]);

        event(new AsPendingEvent($comment));
    }

    public function approve($comment): void
    {
        $comment = $this->getComment($comment);

        $comment->update(['status' => Status::APPROVED]);

        event(new ApprovedEvent($comment));
    }

    public function disapprove($comment): void
    {
        $comment = $this->getComment($comment);

        $comment->update(['status' => Status::DISAPPROVED]);

        event(new DisapprovedEvent($comment));
    }

    /**
     * @param $comment
     * @return Comment
     */
    private function getComment($comment): Comment
    {
        $comment = is_a($comment, Comment::class) ? $comment : Comment::find($comment);
        return $comment;
    }
}
