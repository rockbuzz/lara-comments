<?php

namespace Rockbuzz\LaraComments;

class CommentsService
{
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
