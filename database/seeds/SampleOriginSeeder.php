<?php

use Illuminate\Database\Seeder;
use App\SampleOrigin;

class SampleOriginSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SampleOrigin::Create(['name' => 'Toma de Muestra Domiciliaria','Alias' => 'Toma de Muestra Domiciliaria']);
        SampleOrigin::Create(['name' => 'Gendarmería de Chile','Alias' => 'Gendarmería de Chile']);
        SampleOrigin::Create(['name' => 'Hogar de Ancianos','Alias' => 'Hogar de Ancianos']);
    }
}
