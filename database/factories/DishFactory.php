<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Dish;
use Faker\Generator as Faker;

$factory->define(Dish::class, function (Faker $faker) {
    return [
        'updated_at' => $faker->boolean() ? $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now') : null,
        'deleted_at' => $faker->boolean(30) ? $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now') : null,
        'status' => ''
    ];
});
