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
        EventType::create(['name' => 'Agua potable']);
        EventType::create(['name' => 'Canasta']);
        EventType::create(['name' => 'Hospitalizacion']);
        EventType::create(['name' => 'Licencia']);
        EventType::create(['name' => 'Psícologica']);
    }
}
