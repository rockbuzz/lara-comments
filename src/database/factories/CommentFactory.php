<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Tests\Stubs\{Post, Commenter};
use Rockbuzz\LaraComments\Models\Comment;
use Rockbuzz\LaraComments\Enums\{Type, Status};

$factory->define(Comment::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'body' => $faker->text,
        'likes' => $faker->numberBetween(0, 10),
        'type' => Type::getRandomValue(),
        'status' => Status::getRandomValue(),
        'parent_id' => null,
        'commentable_id' => factory(Post::class)->create()->id,
        'commentable_type' => Post::class,
        'commenter_id' => factory(Commenter::class)->create()->id,
        'commenter_type' => Commenter::class
    ];
});

$factory->state(Comment::class, Status::PENDING, ['status' => Status::PENDING]);
$factory->state(Comment::class, Status::APPROVED, ['status' => Status::APPROVED]);
$factory->state(Comment::class, Status::UNAPPROVED, ['status' => Status::UNAPPROVED]);