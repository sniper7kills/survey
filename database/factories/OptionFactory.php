<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Sniper7Kills\Survey\Models\Option;

$factory->define(Option::class, function (Faker $faker) {
    return [
        'value' => $faker->word,
    ];
});
