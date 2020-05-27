<?php

use Illuminate\Database\Seeder;
use App\Demographic;

class DemographicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $demographic = Demographic::create([
            'street_type' => 'Calle',
            'address' => 'Héroes de la concepción',
            'number' => '2460',
            'department' => 404,
            'region' => 'Tarapacá',
            'commune' => 'Iquique',
            'town' => 'Iquique',
            'telephone' => '576969',
            'latitude' => -20.232030,
            'longitude' => -70.141050,
            'telephone2' => null,
            'email' => 'norma@fabiola.com',
            'region_id' => 1,
            'commune_id' => 5,
            'patient_id' => 1 //Famoso Horacio
        ]);

        $demographic = Demographic::create([
            'street_type' => 'Calle',
            'address' => 'Anibal Pinto',
            'number' => '815',
            'department' => null,
            'region' => 'Tarapacá',
            'commune' => 'Iquique',
            'town' => 'Iquique',
            'telephone' => '576969',
            'latitude' => -20.215820,
            'longitude' => -70.152800,
            'telephone2' => null,
            'email' => 'email@email.com',
            'region_id' => 1,
            'commune_id' => 5,
            'patient_id' => 2 //Famoso Horacio
        ]);
    }
}
