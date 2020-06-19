<?php

use Illuminate\Database\Seeder;
use App\Tracing\EventType;

class EventTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EventType::create(['name' => 'Control telefónico']);
        EventType::create(['name' => 'Derivación a toma de muestra']);
        EventType::create(['name' => 'Entrega de ayuda']);
        EventType::create(['name' => 'Entrega de insumos']);
        EventType::create(['name' => 'Licencia médica']);
        EventType::create(['name' => 'No se pudo contactar']);
        EventType::create(['name' => 'Solicitar traslado hospital']);
        EventType::create(['name' => 'Solicitar traslado residencia']);
        EventType::create(['name' => 'Visita domiciliaria']);
    }
}
