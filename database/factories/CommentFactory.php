<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Tests\Models\{Post, User};
use Rockbuzz\LaraComments\Models\Comment;
use Rockbuzz\LaraComments\Enums\{Type, Status};

$factory->define(Comment::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'body' => $faker->text,
        'type' => Type::getRandomValue(),
        'status' => Status::getRandomValue(),
        'user_id' => factory(User::class)->create()->id,
        'parent_id' => null,
        'commentable_id' => factory(Post::class)->create()->id,
        'commentable_type' => Post::class
    ];
});

$factory->state(Comment::class, Status::PENDING, ['status' => Status::PENDING]);
$factory->state(Comment::class, Status::APPROVED, ['status' => Status::APPROVED]);
$factory->state(Comment::class, Status::UNAPPROVED, ['status' => Status::UNAPPROVED]);