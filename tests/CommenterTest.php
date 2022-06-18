<?php

namespace Tests;

use PDOException;
use Tests\Models\{User, Post};
use Illuminate\Support\Facades\DB;
use Rockbuzz\LaraComments\Models\Comment;
use Illuminate\Database\Eloquent\Relations\{BelongsToMany, HasMany};

class CommenterTest extends TestCase
{
    /** @test */
    public function commenter_can_have_comments()
    {
        $user = $this->create(User::class);
        $post = $this->create(Post::class);
        $this->create(Comment::class, [
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
        $user = $this->create(User::class);
        $post = $this->create(Post::class);

        $comment = $user->commentOn($post, 'comment body', 'comment title');

        $this->assertInstanceOf(Comment::class, $comment);
        $this->assertInstanceOf(HasMany::class, $user->comments());
        $this->assertCount(1, $user->comments);

        $this->assertContains('comment body', $user->comments->pluck('body'));
    }
}
