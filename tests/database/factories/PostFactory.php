<?php

namespace Rockbuzz\LaraComments\Tests\Database\Factories;

use Rockbuzz\LaraComments\Tests\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'content' => $this->faker->text
        ];
    }
}