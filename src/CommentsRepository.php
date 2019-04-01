<?php

namespace Rockbuzz\LaraComments;

use Illuminate\Database\Eloquent\Builder;

class CommentsRepository
{
    public function all(string $commentableType): Builder
    {
        return Comment::with(['commenter', 'commentable'])
            ->where('commentable_type', $commentableType)
            ->latest();
    }

    public function pending(string $commentableType): Builder
    {
        try {
            return Comment::where('commentable_type', $commentableType)
                ->pending()
                ->latest();
        } catch (\Exception $exception) {
            throw new \Exception(
                $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }
    }

    public function approved(string $commentableType): Builder
    {
        try {
            return Comment::where('commentable_type', $commentableType)
                ->approved()
                ->latest();
        } catch (\Exception $exception) {
            throw new \Exception(
                $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }
    }

    public function unapproved(string $commentableType): Builder
    {
        try {
            return Comment::where('commentable_type', $commentableType)
                ->unapproved()
                ->latest();
        } catch (\Exception $exception) {
            throw new \Exception(
                $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }
    }

    public function approve(Comment $comment)
    {
        try {
            $comment->update(['state' => State::APPROVED]);
            return redirect()->back()
                ->withSuccess(config('comments.messages.approve'));
        } catch (\Exception $exception) {
            throw new \Exception(
                $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }
    }

    public function unapprove(Comment $comment)
    {
        try {
            $comment->update(['state' => State::UNAPPROVED]);
            return redirect()->back()
                ->withSuccess(config('comments.messages.unapprove'));
        } catch (\Exception $exception) {
            throw new \Exception(
                $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }
    }

    public function delete(Comment $comment)
    {
        try {
            $comment->delete();
            return redirect()->back()
                ->withSuccess(config('comments.messages.delete'));
        } catch (\Exception $exception) {
            throw new \Exception(
                $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }
    }
}
