<?php

namespace Tests;

use Rockbuzz\LaraUuid\Traits\Uuid;
use Tests\Stubs\{Post, Commenter};
use Illuminate\Support\Facades\Config;
use Rockbuzz\LaraComments\Models\Comment;
use Rockbuzz\LaraComments\Enums\{Status};
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\{HasMany, MorphTo, BelongsTo};

class CommentTest extends TestCase
{
    protected $comment;

    public function setUp(): void
    {
        parent::setUp();

        $this->comment = new Comment();
    }

    public function testIfUsesTraits()
    {
        $expected = [
            Uuid::class,
            SoftDeletes::class
        ];

        $this->assertEquals(
            $expected,
            array_values(class_uses(Comment::class))
        );
    }

    public function testIncrementing()
    {
        $this->assertFalse($this->comment->incrementing);
    }

    public function testKeyType()
    {
        $this->assertEquals('string', $this->comment->getKeyType());
    }

    public function testFillable()
    {
        $expected = [
            'title',
            'body',
            'likes',
            'type',
            'status',
            'parent_id',
            'commentable_id',
            'commentable_type',
            'commenter_id',
            'commenter_type'
        ];

        $this->assertEquals($expected, $this->comment->getFillable());
    }

    public function testCasts()
    {
        $expected = [
            'id' => 'string',
            'likes' => 'integer',
            'type' => 'integer',
            'status' => 'integer'
        ];

        $this->assertEquals($expected, $this->comment->getCasts());
    }

    public function testDates()
    {
        $this->assertEquals(
            array_values(['deleted_at', 'created_at', 'updated_at']),
            array_values($this->comment->getDates())
        );
    }

    public function testCommentHasCommenter()
    {
        Config::set('comments.models.commenter', Commenter::class);

        $commenter = $this->create(Commenter::class);
        $comment = $this->create(Comment::class, [
            'commenter_id' => $commenter->id,
            'commenter_type' => Commenter::class
        ]);

        $this->assertInstanceOf(MorphTo::class, $comment->commenter());
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
        $this->assertEquals($post->id, $comment->commentable->id);
    }

    public function testCommentCanHaveChildren()
    {
        $comment = $this->create(Comment::class);

        $children = $this->create(Comment::class, [
            'parent_id' => $comment->id
        ]);

        $this->assertInstanceOf(HasMany::class, $comment->children());
        $this->assertContains($children->id, $comment->children->pluck('id'));
    }

    public function testCommentCanHaveParent()
    {
        $parent = $this->create(Comment::class);

        $comment = $this->create(Comment::class, [
            'parent_id' => $parent->id
        ]);

        $this->assertInstanceOf(BelongsTo::class, $comment->parent());
        $this->assertEquals($parent->id, $comment->parent->id);
    }

    public function testCommentIsPending()
    {
        $comment = factory(Comment::class)->states(Status::APPROVED)->create();

        $this->assertFalse($comment->isPending());

        $comment->update(['status' => Status::PENDING]);

        $this->assertTrue($comment->isPending());
    }

    public function testCommentIsApproved()
    {
        $comment = factory(Comment::class)->states(Status::PENDING)->create();

        $this->assertFalse($comment->isApproved());

        $comment->update(['status' => Status::APPROVED]);

        $this->assertTrue($comment->isApproved());
    }

    public function testCommentIsUnapproved()
    {
        $comment = factory(Comment::class)->states(Status::PENDING)->create();

        $this->assertFalse($comment->isUnapproved());

        $comment->update(['status' => Status::UNAPPROVED]);

        $this->assertTrue($comment->isUnapproved());
    }

    public function testCommentScopePending()
    {
        $pendingComments = factory(Comment::class, 5)->states(Status::PENDING)->create();
        $approvedComment = factory(Comment::class)->states(Status::APPROVED)->create();
        $disapprovedComment = factory(Comment::class)->states(Status::UNAPPROVED)->create();

        $pendingComments->each(function ($comment) {
            $this->assertContains($comment->id, Comment::pending()->get()->pluck('id'));
        });

        $this->assertNotContains($approvedComment->id, Comment::pending()->get()->pluck('id'));
        $this->assertNotContains($disapprovedComment->id, Comment::pending()->get()->pluck('id'));
    }

    public function testCommentScopeApproved()
    {
        $pendingComment = factory(Comment::class)->states(Status::PENDING)->create();
        $approvedComments = factory(Comment::class, 5)->states(Status::APPROVED)->create();
        $disapprovedComment = factory(Comment::class)->states(Status::UNAPPROVED)->create();

        $approvedComments->each(function ($comment) {
            $this->assertContains($comment->id, Comment::approved()->get()->pluck('id'));
        });

        $this->assertNotContains($pendingComment->id, Comment::approved()->get()->pluck('id'));
        $this->assertNotContains($disapprovedComment->id, Comment::approved()->get()->pluck('id'));
    }

    public function testCommentScopeDisapproved()
    {
        $pendingComment = factory(Comment::class)->states(Status::PENDING)->create();
        $approvedComment = factory(Comment::class)->states(Status::APPROVED)->create();
        $disapprovedComments = factory(Comment::class, 5)->states(Status::UNAPPROVED)->create();

        $disapprovedComments->each(function ($comment) {
            $this->assertContains($comment->id, Comment::disapproved()->get()->pluck('id'));
        });

        $this->assertNotContains($pendingComment->id, Comment::disapproved()->get()->pluck('id'));
        $this->assertNotContains($approvedComment->id, Comment::disapproved()->get()->pluck('id'));
    }
}
