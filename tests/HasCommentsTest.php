<?php

namespace Tests;

use Tests\Models\User;
use Rockbuzz\LaraComments\Comment;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class HasCommentsTest extends TestCase
{
    public function testUserHasComments()
    {
        $user = $this->create(User::class);
        $comment = $this->create(Comment::class, [
            'commenter_id' => $user->id,
            'commenter_type' => User::class
        ]);

        $this->assertInstanceOf(MorphMany::class, $user->comments());
        $this->assertContains($comment->id, $user->comments->pluck('id'));
    }
}
