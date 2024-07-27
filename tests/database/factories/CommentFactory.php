<?php

namespace Rockbuzz\LaraComments\Tests\database\factories;

use Rockbuzz\LaraComments\Models\Comment;
use Rockbuzz\LaraComments\Enums\{Status, Type};
use Illuminate\Database\Eloquent\Factories\Factory;
use Rockbuzz\LaraComments\Tests\Models\{Post, User};

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'body' => $this->faker->text,
            'type' => Type::getRandomValue(),
            'status' => Status::getRandomValue(),
            'user_id' => User::factory()->create()->id,
            'parent_id' => null,
            'commentable_id' => Post::factory()->create()->id,
            'commentable_type' => Post::class
        ];
    }

    public function pending(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => Status::PENDING,
        ]);
    }

    public function approved(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => Status::APPROVED,
        ]);
    }

    public function unapproved(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => Status::UNAPPROVED,
        ]);
    }
}