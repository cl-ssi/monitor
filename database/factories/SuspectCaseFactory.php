<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\SuspectCase;
use App\Patient;
use App\Establishment;
use Faker\Generator as Faker;

$factory->define(SuspectCase::class, function (Faker $faker) {
    //Necesito genero y edad.
    //Por el contrario, necesito saber el patient_id asignado
    //de forma implicita.
    //Otra Alternativa, saber ultimo registro de patient ingresado a db
    //$patient = Patient::find(90);

    $gender = $faker->randomElement(['male', 'female']);
    $age = rand(1,110);
    $symptoms=rand(0,1);
    $gestation=($gender=='female'&&$age>=16)?rand(0,1):0;
    $gestation_week=($gestation==1)?rand(1,40):0;
    $sample_at = $faker->dateTimeBetween('2020-03-03','now');
    $sample_at_sub_7 = clone $sample_at;
    $sample_at_add_3 = clone $sample_at;
    date_sub($sample_at_sub_7, date_interval_create_from_date_string('7 days'));
    date_add($sample_at_add_3, date_interval_create_from_date_string('3 days'));
    $symptoms_at = $faker->dateTimeBetween($sample_at_sub_7,$sample_at);
    $reception_at = $faker->dateTimeBetween($sample_at,$sample_at_add_3);
    $reception_at_add_3 = clone $reception_at;
    date_add($reception_at_add_3, date_interval_create_from_date_string('3 days'));
    $pcr_sars_cov_2_at = $faker->dateTimeBetween($reception_at,$reception_at_add_3);
    return [
        //
        //'patient_id' => function (array $post) {
        //            return Patient::find($post['patient_id']);
        //            },
        //'patient_id' => null,
        //'age' => function(array $post){return App\Patient::find($post['patient_id'])->age;},
        'age' => $age,
        'gender' => $gender,
        'sample_at' => $sample_at,
        'epidemiological_week' => null,
        'origin' => null,
        'run_medic' => rand(1000000,26000000),
        'symptoms' => $symptoms,
        //fechas entre 1 y 7 días antes de sample_at
        'symptoms_at' => ($symptoms==1)?$symptoms_at:null,
        //fechas entre 1 y 3 días despues de sample_at
        'reception_at' => $reception_at,
        'receptor_id' => 1,
        'result_ifd_at' => null,
        'result_ifd' => null,
        'subtype' => null,
        //fechas entre 1 y 3 dias despues de reception_at
        'pcr_sars_cov_2_at' => $pcr_sars_cov_2_at,
        'pcr_sars_cov_2' => $faker->randomElement(['PENDING', 'POSITIVE', 'NEGATIVE', 'UNDETERMINED', 'REJECTED']),
        'sample_type' => $faker->randomElement(['TORULAS NASOFARINGEAS', 'ESPUTO', 'ASPIRADO NASOFARÍNGEO', 'LAVADO BRONCOALVEOLAR', 'ASPIRADO TRAQUEAL', 'MUESTRA SANGUÍNEA', 'TEJIDO PULMONAR', 'SALIVA', 'OTRO']),
        'validator_id' => 1,
        'sent_external_lab_at' => null,
        'external_laboratory' => null,
        'paho_flu' => null,
        'epivigila' => null,
        'gestation' => $gestation,
        'gestation_week' => $gestation_week,
        'close_contact' => rand(0,1),
        'functionary' => rand(0,1),
        'notification_at' => null,
        'notification_mechanism' => null,
        'discharged_at' => null,
        'observation' => $faker->realText($maxNbChars = 50, $indexSize = 1),
        'minsal_ws_id' => null,
        //'patient_id' => 1,
        //'patient_id' => $patient->id,
        'laboratory_id' => rand(1,3),
        'establishment_id' => $faker->randomElement(Establishment::whereIn('commune_id', explode(',', env('COMUNAS') ))->pluck('id')->toArray()),
        'user_id' => 1
    ];
});
