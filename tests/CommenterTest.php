<?php

namespace Rockbuzz\LaraComments\Tests;

use PDOException;
use Rockbuzz\LaraComments\Tests\Models\{User, Post};
use Illuminate\Support\Facades\DB;
use Rockbuzz\LaraComments\Models\Comment;
use Illuminate\Database\Eloquent\Relations\{BelongsToMany, HasMany};

class CommenterTest extends TestCase
{
    /** @test */
    public function commenter_can_have_comments()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        Comment::factory()->create([
            'user_id' => $user->id,
            'commentable_id' => $post->id,
            'commentable_type' => Post::class
        ]);

        $this->assertInstanceOf(HasMany::class, $user->comments());
        $this->assertCount(1, $user->comments);
    }

    /** @test */
    public function commenter_can_comment_on()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $comment = $user->commentOn($post, 'comment body', 'comment title');

        $this->assertInstanceOf(Comment::class, $comment);
        $this->assertInstanceOf(HasMany::class, $user->comments());
        $this->assertCount(1, $user->comments);

        $this->assertContains('comment body', $user->comments->pluck('body'));
    }

    /** @test */
    public function comment_can_have_likes()
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create();

        DB::table('likes')->insert([
            'user_id' => $user->id,
            'comment_id' => $comment->id
        ]);

        $this->assertInstanceOf(BelongsToMany::class, $user->likes());
        $this->assertCount(1, $user->likes);
    }

    /** @test */
    public function comment_can_like()
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create();

        $user->likeTo($comment);

        $this->assertInstanceOf(BelongsToMany::class, $user->likes());
        $this->assertCount(1, $user->likes);
    }

    /** @test */
    public function comment_can_dislike()
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create();

        DB::table('likes')->insert([
            'user_id' => $user->id,
            'comment_id' => $comment->id
        ]);

        $user->dislikeTo($comment);

        $this->assertInstanceOf(BelongsToMany::class, $user->likes());
        $this->assertCount(0, $user->likes);
    }

    /** @test */
    public function comment_like_must_return_pdo_exception_when_record_already_exists()
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create();

        DB::table('likes')->insert([
            'user_id' => $user->id,
            'comment_id' => $comment->id
        ]);

        $this->expectException(PDOException::class);

        $user->likeTo($comment);
    }
}