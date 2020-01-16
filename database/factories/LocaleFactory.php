<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Locale;
use Faker\Generator as Faker;

$factory->define(Locale::class, function (Faker $faker) {
    {
        return [
            'locale' => $faker->randomElement($array = array('en','hr','de','fr','it')),
            'created_at' => $faker->dateTimeBetween($startDate = '-3 years', $endDate = '-2 years'),
            'updated_at' => $faker->dateTimeBetween($startDate = '-2 years', $endDate = '-1 years')
        ];
    }
});
