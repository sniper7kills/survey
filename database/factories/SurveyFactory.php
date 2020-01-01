<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Carbon\Carbon;
use Faker\Generator as Faker;
use Sniper7Kills\Survey\Models\Survey;

$factory->define(Survey::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence,
        'description' => $faker->paragraph,
        'available_at' => Carbon::now()
    ];
});
