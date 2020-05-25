<?php

use App\Establishment;
use Illuminate\Database\Seeder;

class EstablishmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Establishment::Create([
            'id' => 6,
            'name' => 'Centro de Salud Familiar Cirujano Aguirre',
            'alias' => 'CESFAM Aguirre',
            'type' => 'Centro de Salud Familiar',
            'code_deis' => 102300,
            'service' => 'SERVICIO DE SALUD IQUIQUE',
            'dependency' => 'Municipal',
            'commune' => 'IQUIQUE',
            'commune_code_deis' => '102-602',
            'commune_id' => 5
        ]);
    }
}
