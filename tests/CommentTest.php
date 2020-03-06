<?php

namespace Tests;

use Tests\Models\{Post, User};
use Rockbuzz\LaraComments\Comment;
use Illuminate\Support\Facades\Config;
use Rockbuzz\LaraComments\Enums\{Status};
use Illuminate\Database\Eloquent\Relations\{HasMany, MorphTo, BelongsTo};

class CommentTest extends TestCase
{
    public function testCommentHasCommenter()
    {
        Config::set('comments.models.commenter', User::class);

        $commenter = $this->create(User::class);
        $comment = $this->create(Comment::class, [
            'commenter_id' => $commenter->id,
            'commenter_type' => User::class
        ]);

        $this->assertInstanceOf(BelongsTo::class, $comment->commenter());
        $this->assertEquals($commenter->id, $comment->commenter->id);
    }

    public function testCommentHasCommentable()
    {
        $post = $this->create(Post::class);

        $comment = $this->create(Comment::class, [
            'commentable_id' => $post->id,
            'commentable_type' => Post::class
        ]);

        $this->assertInstanceOf(MorphTo::class, $comment->commentable());
        $this->assertContains($post->id, $comment->commentable->pluck('id'));
    }

    public function testCommentCanHaveChildren()
    {
        $comment = $this->create(Comment::class);

        $children = $this->create(Comment::class, [
            'comment_id' => $comment->id
        ]);

        $this->assertInstanceOf(HasMany::class, $comment->children());
        $this->assertContains($children->id, $comment->children->pluck('id'));
    }

    public function testCommentCanHaveParent()
    {
        $parent = $this->create(Comment::class);

        $comment = $this->create(Comment::class, [
            'comment_id' => $parent->id
        ]);

        $this->assertInstanceOf(BelongsTo::class, $comment->parent());
        $this->assertEquals($parent->id, $comment->parent->id);
    }

    public function testCommentStatus()
    {
        $comment = $this->create(Comment::class, [
            'status' => Status::APPROVED
        ]);

        $comment->asPending();

        $this->assertTrue($comment->isPending());
        $this->assertFalse($comment->isApproved());
        $this->assertFalse($comment->isDisapproved());

        $comment->approve();

        $this->assertFalse($comment->isPending());
        $this->assertTrue($comment->isApproved());
        $this->assertFalse($comment->isDisapproved());

        $comment->disapprove();

        $this->assertFalse($comment->isPending());
        $this->assertFalse($comment->isApproved());
        $this->assertTrue($comment->isDisapproved());
    }

    public function testCommentScopePending()
    {
        $pendingComments = $this->create(Comment::class, [
            'status' => Status::PENDING
        ], 5);
        $approvedComment = $this->create(Comment::class, [
            'status' => Status::APPROVED
        ]);
        $disapprovedComment = $this->create(Comment::class, [
            'status' => Status::DISAPPROVED
        ]);

        $pendingComments->each(function ($comment) {
            $this->assertContains($comment->id, Comment::pending()->get()->pluck('id'));
        });

        $this->assertNotContains($approvedComment->id, Comment::pending()->get()->pluck('id'));
        $this->assertNotContains($disapprovedComment->id, Comment::pending()->get()->pluck('id'));
    }

    public function testCommentScopeApproved()
    {
        $pendingComment = $this->create(Comment::class, [
            'status' => Status::PENDING
        ]);
        $approvedComments = $this->create(Comment::class, [
            'status' => Status::APPROVED
        ], 5);
        $disapprovedComment = $this->create(Comment::class, [
            'status' => Status::DISAPPROVED
        ]);

        $approvedComments->each(function ($comment) {
            $this->assertContains($comment->id, Comment::approved()->get()->pluck('id'));
        });

        $this->assertNotContains($pendingComment->id, Comment::approved()->get()->pluck('id'));
        $this->assertNotContains($disapprovedComment->id, Comment::approved()->get()->pluck('id'));
    }

    public function testCommentScopeDisapproved()
    {
        $pendingComment = $this->create(Comment::class, [
            'status' => Status::PENDING
        ]);
        $approvedComment = $this->create(Comment::class, [
            'status' => Status::APPROVED
        ]);
        $disapprovedComments = $this->create(Comment::class, [
            'status' => Status::DISAPPROVED
        ], 5);

        $disapprovedComments->each(function ($comment) {
            $this->assertContains($comment->id, Comment::disapproved()->get()->pluck('id'));
        });

        $this->assertNotContains($pendingComment->id, Comment::disapproved()->get()->pluck('id'));
        $this->assertNotContains($approvedComment->id, Comment::disapproved()->get()->pluck('id'));
    }
}
