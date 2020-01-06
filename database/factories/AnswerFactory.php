<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Sniper7Kills\Survey\Models\Answer;

$factory->define(Answer::class, function (Faker $faker) {
    return [
        'answer' => $faker->sentence
    ];
});
