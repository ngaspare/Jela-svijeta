<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\CategoryTranslation;
use Faker\Generator as Faker;

$factory->define(CategoryTranslation::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'updated_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now')
    ];
});
