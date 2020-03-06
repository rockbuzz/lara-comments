<?php

namespace Tests;

use Tests\Models\Post;
use Rockbuzz\LaraComments\Comment;
use Illuminate\Database\Eloquent\Relations\MorphMany;

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
}
