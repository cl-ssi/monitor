<?php

use Illuminate\Database\Seeder;
use App\Tracing\RequestType;

class RequestTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RequestType::create(['name' => 'Agua potable']);
        RequestType::create(['name' => 'Canasta']);
        RequestType::create(['name' => 'Hospitalizacion']);
        RequestType::create(['name' => 'Licencia Medica APS']);
        RequestType::create(['name' => 'Licencia Medica SEREMI']);
        RequestType::create(['name' => 'Psícologica']);
    }
}
