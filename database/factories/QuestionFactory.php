<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Sniper7Kills\Survey\Models\Question;

$factory->define(Question::class, function (Faker $faker) {
    return [
        'question' => $faker->sentence,
        'type' => 'text',
    ];
});
