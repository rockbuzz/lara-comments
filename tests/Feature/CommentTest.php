<?php

namespace Tests\Feature;

use Rockbuzz\LaraComments\CommentsService;
use Rockbuzz\LaraComments\State;
use Tests\Models\Post;
use Tests\Models\User;
use Tests\TestCase;

class CommentTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldUpdateCommentStateToApproved()
    {
        $user = User::create([
            'name' => 'User Test',
            'email' => 'user.test@email.com',
            'password' => bcrypt('123456')
        ]);

        $post = Post::create([
            'title' => 'Title Test',
            'content' => 'Content Test'
        ]);

        $comment = $post->comments()->create([
            'content' => 'Content Comment Test',
            'commenter_id' => $user->id,
            'state' => State::PENDING
        ]);

        $controller = new CommentsService();
        $controller->approve($comment);

        $this->assertDatabaseHas('comments', [
            'state' => State::APPROVED
        ]);
    }

    /**
     * @test
     */
    public function itShouldUpdateCommentStateToUnapproved()
    {
        $user = User::create([
            'name' => 'User Test',
            'email' => 'user.test@email.com',
            'password' => bcrypt('123456')
        ]);

        $post = Post::create([
            'title' => 'Title Test',
            'content' => 'Content Test'
        ]);

        $comment = $post->comments()->create([
            'content' => 'Content Comment Test',
            'commenter_id' => $user->id,
            'state' => State::APPROVED
        ]);

        $controller = new CommentsService();
        $controller->unapprove($comment);

        $this->assertDatabaseHas('comments', [
            'state' => State::UNAPPROVED
        ]);
    }

    /**
     * @test
     */
    public function itShouldDeleteComment()
    {
        $user = User::create([
            'name' => 'User Test',
            'email' => 'user.test@email.com',
            'password' => bcrypt('123456')
        ]);

        $post = Post::create([
            'title' => 'Title Test',
            'content' => 'Content Test'
        ]);

        $comment = $post->comments()->create([
            'content' => 'Content Comment Test',
            'commenter_id' => $user->id,
            'state' => State::APPROVED
        ]);

        $controller = new CommentsService();
        $controller->delete($comment);

        $this->assertDatabaseMissing('comments', [
            'id' => $comment->id
        ]);
    }
}
