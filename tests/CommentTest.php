<?php

namespace Tests;

use PDOException;
use Tests\Stubs\{Post, User};
use Illuminate\Support\Facades\DB;
use Rockbuzz\LaraComments\Models\Comment;
use Rockbuzz\LaraComments\Enums\{Status};
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\{HasMany, MorphTo, BelongsTo, BelongsToMany};

class CommentTest extends TestCase
{
    protected $comment;

    public function setUp(): void
    {
        parent::setUp();

        $this->comment = new Comment();
    }

    /** @test */
    public function comment_uses_traits()
    {
        $expected = [
            SoftDeletes::class
        ];

        $this->assertEquals(
            $expected,
            array_values(class_uses(Comment::class))
        );
    }

    /** @test */
    public function comment_has_fillable()
    {
        $expected = [
            'title',
            'body',
            'type',
            'status',
            'user_id',
            'parent_id',
            'commentable_id',
            'commentable_type'
        ];

        $this->assertEquals($expected, $this->comment->getFillable());
    }

    /** @test */
    public function comment_has_casts()
    {
        $expected = [
            'id' => 'int',
            'type' => 'integer',
            'status' => 'integer',
            'deleted_at' => 'datetime'
        ];

        $this->assertEquals($expected, $this->comment->getCasts());
    }

    /** @test */
    public function comment_has_dates()
    {
        $this->assertEquals(
            array_values(['deleted_at', 'created_at', 'updated_at']),
            array_values($this->comment->getDates())
        );
    }

    /** @test */
    public function comment_has_user()
    {
        $commenter = $this->create(User::class);
        $comment = $this->create(Comment::class, [
            'user_id' => $commenter->id
        ]);

        $this->assertInstanceOf(BelongsTo::class, $comment->commenter());
        $this->assertEquals($commenter->id, $comment->commenter->id);
    }

    /** @test */
    public function comment_has_commentable()
    {
        $post = $this->create(Post::class);

        $comment = $this->create(Comment::class, [
            'commentable_id' => $post->id,
            'commentable_type' => Post::class
        ]);

        $this->assertInstanceOf(MorphTo::class, $comment->commentable());
        $this->assertEquals($post->id, $comment->commentable->id);
    }

    /** @test */
    public function comment_can_have_children()
    {
        $comment = $this->create(Comment::class);

        $children = $this->create(Comment::class, [
            'parent_id' => $comment->id
        ]);

        $this->assertInstanceOf(HasMany::class, $comment->children());
        $this->assertContains($children->id, $comment->children->pluck('id'));
    }

    /** @test */
    public function comment_can_have_parent()
    {
        $parent = $this->create(Comment::class);

        $comment = $this->create(Comment::class, [
            'parent_id' => $parent->id
        ]);

        $this->assertInstanceOf(BelongsTo::class, $comment->parent());
        $this->assertEquals($parent->id, $comment->parent->id);
    }

    /** @test */
    public function comment_is_pending()
    {
        $comment = factory(Comment::class)->states(Status::APPROVED)->create();

        $this->assertFalse($comment->isPending());

        $comment->update(['status' => Status::PENDING]);

        $this->assertTrue($comment->isPending());
    }

    /** @test */
    public function comment_is_approved()
    {
        $comment = factory(Comment::class)->states(Status::PENDING)->create();

        $this->assertFalse($comment->isApproved());

        $comment->update(['status' => Status::APPROVED]);

        $this->assertTrue($comment->isApproved());
    }

    /** @test */
    public function comment_is_unapproved()
    {
        $comment = factory(Comment::class)->states(Status::PENDING)->create();

        $this->assertFalse($comment->isUnapproved());

        $comment->update(['status' => Status::UNAPPROVED]);

        $this->assertTrue($comment->isUnapproved());
    }

    /** @test */
    public function comment_has_scope_pending()
    {
        $pendingComments = factory(Comment::class, 5)->states(Status::PENDING)->create();
        $approvedComment = factory(Comment::class)->states(Status::APPROVED)->create();
        $unapprovedComment = factory(Comment::class)->states(Status::UNAPPROVED)->create();

        $pendingComments->each(function ($comment) {
            $this->assertContains($comment->id, Comment::pending()->get()->pluck('id'));
        });

        $this->assertNotContains($approvedComment->id, Comment::pending()->get()->pluck('id'));
        $this->assertNotContains($unapprovedComment->id, Comment::pending()->get()->pluck('id'));
    }

    /** @test */
    public function comment_has_scope_approved()
    {
        $pendingComment = factory(Comment::class)->states(Status::PENDING)->create();
        $approvedComments = factory(Comment::class, 5)->states(Status::APPROVED)->create();
        $unapprovedComment = factory(Comment::class)->states(Status::UNAPPROVED)->create();

        $approvedComments->each(function ($comment) {
            $this->assertContains($comment->id, Comment::approved()->get()->pluck('id'));
        });

        $this->assertNotContains($pendingComment->id, Comment::approved()->get()->pluck('id'));
        $this->assertNotContains($unapprovedComment->id, Comment::approved()->get()->pluck('id'));
    }

    /** @test */
    public function comment_has_scope_unapproved()
    {
        $pendingComment = factory(Comment::class)->states(Status::PENDING)->create();
        $approvedComment = factory(Comment::class)->states(Status::APPROVED)->create();
        $unapprovedComments = factory(Comment::class, 5)->states(Status::UNAPPROVED)->create();

        $unapprovedComments->each(function ($comment) {
            $this->assertContains($comment->id, Comment::unapproved()->get()->pluck('id'));
        });

        $this->assertNotContains($pendingComment->id, Comment::unapproved()->get()->pluck('id'));
        $this->assertNotContains($approvedComment->id, Comment::unapproved()->get()->pluck('id'));
    }
}
