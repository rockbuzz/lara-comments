<?php

namespace Tests;

use Tests\Stubs\Post;
use Illuminate\Support\Facades\Event;
use Rockbuzz\LaraComments\Enums\Status;
use Rockbuzz\LaraComments\Models\Comment;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Rockbuzz\LaraComments\Events\{ApprovedEvent, AsPendingEvent, UnapprovedEvent};

class CommentableTest extends TestCase
{
    public function testPostHasComments()
    {
        $post = $this->create(Post::class);
        $comment = $this->create(Comment::class, [
            'commentable_id' => $post->id,
            'commentable_type' => Post::class
        ]);

        $this->assertInstanceOf(MorphMany::class, $post->comments());
        $this->assertContains($comment->id, $post->comments->pluck('id'));
    }

    public function testPostCanHaveCommentPending()
    {
        Event::fake([AsPendingEvent::class]);

        $post = $this->create(Post::class);

        $comment = $this->create(Comment::class, [
            'status' => Status::APPROVED,
            'commentable_id' => $post->id,
            'commentable_type' => Post::class
        ]);

        $post->asPending($comment);

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'status' => Status::PENDING
        ]);

        $comment->update(['status' => Status::PENDING]);

        $post->asPending($comment->id);

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'status' => Status::PENDING
        ]);

        Event::assertDispatched(AsPendingEvent::class, function ($e) use ($comment) {
            return $e->comment->id === $comment->id;
        });
    }

    public function testPostCanHaveCommentApproved()
    {
        Event::fake([ApprovedEvent::class]);

        $post = $this->create(Post::class);

        $comment = $this->create(Comment::class, [
            'status' => Status::PENDING,
            'commentable_id' => $post->id,
            'commentable_type' => Post::class
        ]);

        $post->approve($comment);

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'status' => Status::APPROVED
        ]);

        $comment->update(['status' => Status::PENDING]);

        $post->approve($comment->id);

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'status' => Status::APPROVED
        ]);

        Event::assertDispatched(ApprovedEvent::class, function ($e) use ($comment) {
            return $e->comment->id === $comment->id;
        });
    }

    public function testPostCanHaveCommentDisapproved()
    {
        Event::fake([UnapprovedEvent::class]);

        $post = $this->create(Post::class);

        $comment = $this->create(Comment::class, [
            'status' => Status::PENDING,
            'commentable_id' => $post->id,
            'commentable_type' => Post::class
        ]);

        $post->unapprove($comment);

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'status' => Status::UNAPPROVED
        ]);

        $comment->update(['status' => Status::PENDING]);

        $post->unapprove($comment->id);

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'status' => Status::UNAPPROVED
        ]);

        Event::assertDispatched(UnapprovedEvent::class, function ($e) use ($comment) {
            return $e->comment->id === $comment->id;
        });
    }
}
