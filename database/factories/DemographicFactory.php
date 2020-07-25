<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Demographic;
use App\Patient;
use App\Commune;
use Faker\Generator as Faker;

$factory->define(Demographic::class, function (Faker $faker) {
    //print_r($attributes);
    //env('COMUNAS')
    //env('LATITUD')
    //$gestation=($patient->gender=='female'&&$patient->getAgeAttribute()>=16)?$faker->numberBetween(0,1):0;
    $commune = Commune::where('id',$faker->randomElement(explode(',', env('COMUNAS') )))->get();
    return [
        //
        'street_type' => $faker->randomElement(['calle', 'pasaje', 'avenida', 'camino']),
        'address' => $faker->word,
        'number' => $faker->numberBetween($min = 100, $max = 20000),
        'department' => null,
        'suburb' => $faker->word,
        'region_id' => $commune->first()->region_id,
        'commune_id' => $commune->first()->id,
        'city' => $faker->city,
        'nationality' => $faker->country,
        'latitude' => env('LATITUD')+rand(-100,100)/100,
        'longitude' => env('LONGITUD')+rand(-100,100)/100,
        'telephone' => $faker->phoneNumber,
        //'telephone2' => $faker->phoneNumber,
        'email' => $faker->email,
        //'patient_id' => $patient
        //'patient_id' => factory(App\Patient::class)->create()->id
    ];
});
