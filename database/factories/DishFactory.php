<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Dish;
use Faker\Generator as Faker;

$factory->define(Dish::class, function (Faker $faker) {
    return [
        'status' => $faker->randomElement($array = array ('created', 'modified', 'deleted')),
        'updated_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now')
    ];
});
