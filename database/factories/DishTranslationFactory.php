<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\DishTranslation
;
use Faker\Generator as Faker;

$factory->define(DishTranslation::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'description' => $faker->sentence,
        'updated_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now')
    ];
});
