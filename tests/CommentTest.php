<?php

namespace Tests;

use Rockbuzz\LaraComments\State;
use Tests\Models\Post;
use Tests\Models\User;

class CommentTest extends TestCase
{
    /**
     * @test
     */
    public function mustCreateACommentForAPost()
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
            'commenter_id' => $user->id
        ]);

        $this->assertDatabaseHas('comments', [
            'content' => 'Content Comment Test',
            'commenter_id' => $user->id,
            'commentable_id' => $post->id,
            'commentable_type' => Post::class
        ]);

        $this->assertEquals($comment->commentable->id, $post->id);
    }

    /**
     * @test
     */
    public function mustCreateAReplyToAComment()
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
            'commenter_id' => $user->id
        ]);

        $userReply = User::create([
            'name' => 'User Reply',
            'email' => 'user.reply@email.com',
            'password' => bcrypt('123456')
        ]);

        $post->comments()->create([
            'content' => 'Content Reply',
            'commenter_id' => $userReply->id,
            'comment_id' => $comment->id
        ]);

        $reply = $comment->children()->first();

        $this->assertEquals('Content Reply', $reply->content);
        $this->assertEquals($reply->parent->content, $comment->content);
    }

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

        $comment->approve();

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

        $comment->disapprove();

        $this->assertDatabaseHas('comments', [
            'state' => State::DISAPPROVED
        ]);
    }

    /**
     * @test
     */
    public function itShouldAsPendingComment()
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

        $comment->asPending();

        $this->assertDatabaseHas('comments', [
            'state' => State::PENDING
        ]);
    }
}
