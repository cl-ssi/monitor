<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Patient;
use Faker\Generator as Faker;

$factory->define(Patient::class, function (Faker $faker) {
    return [
                //'id' => 2,
//                'id', 'run', 'dv', 'other_identification', 'name', 'fathers_family',
//                'mothers_family', 'gender', 'birthday', 'status', 'deceased_at'
//
                'run' => "11418555",
                'dv' => '3',
                'other_identification' => "8786554",
                'name' => $faker->name(),
                'fathers_family' => $faker->lastName(),
                'mothers_family' => $faker->lastName(),
                'gender' => "male",
                'birthday' => null,
                'status' => "cuarentena---",
                'deceased_at' => null
    ];
});
