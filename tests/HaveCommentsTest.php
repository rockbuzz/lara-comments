<?php

namespace Tests;

use Tests\Stubs\Commenter;
use Rockbuzz\LaraComments\Models\Comment;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class HaveCommentsTest extends TestCase
{
    public function testCommenterCanHaveComments()
    {
        $commenter = $this->create(Commenter::class);
        $comment = $this->create(Comment::class, [
            'commenter_id' => $commenter->id,
            'commenter_type' => Commenter::class
        ]);

        $this->assertInstanceOf(MorphMany::class, $commenter->comments());
        $this->assertContains($comment->id, $commenter->comments->pluck('id'));
    }

    public function testCommenterHasComments()
    {
        $commenter = $this->create(Commenter::class);

        $this->assertFalse($commenter->hasComments());

        $comment = $this->create(Comment::class, [
            'commenter_id' => $commenter->id,
            'commenter_type' => Commenter::class
        ]);

        $this->assertTrue($commenter->hasComments());
    }
}
