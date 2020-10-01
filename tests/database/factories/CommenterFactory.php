<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Tests\Stubs\Commenter;
use Faker\Generator as Faker;

$factory->define(Commenter::class, function (Faker $faker) {
    return [
        'name' => $faker->name
    ];
});
