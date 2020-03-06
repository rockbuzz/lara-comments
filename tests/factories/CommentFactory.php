<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Tests\Models\{Post, User};
use Rockbuzz\LaraComments\Comment;
use Rockbuzz\LaraComments\Enums\{Type, Status};

$factory->define(Comment::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'body' => $faker->text,
        'likes' => $faker->numberBetween(0, 10),
        'type' => Type::getRandomValue(),
        'status' => Status::getRandomValue(),
        'comment_id' => null,
        'commentable_id' => factory(Post::class)->create()->id,
        'commentable_type' => Post::class,
        'commenter_id' => factory(User::class)->create()->id,
        'commenter_type' => User::class
    ];
});
