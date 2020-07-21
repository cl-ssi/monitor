<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Patient;
use Faker\Generator as Faker;

//$faker = Faker::create('es_SP');

$factory->define(Patient::class, function (Faker $faker) {
    return [
        'run' => $faker->numberBetween($min = 1000000, $max = 26000000),
        'dv' => $faker->randomElement(['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'k']),
        'other_identification' => "8786554",
        'name' => $faker->name(),
        'fathers_family' => $faker->lastName(),
        'mothers_family' => $faker->lastName(),
        'gender' => $faker->randomElement(['male', 'female']),
        'birthday' => $faker->dateTimeBetween('-95 years', '-1 days'),
        'status' => "Ambulatorio",
        'deceased_at' => null
    ];
});
