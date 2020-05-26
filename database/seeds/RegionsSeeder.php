<?php

use App\Region;
use Illuminate\Database\Seeder;

class RegionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Region::Create(['name' => 'Región De Tarapacá']);
        Region::Create(['name' => 'Región De Antofagasta']);
        Region::Create(['name' => 'Región De Atacama']);
        Region::Create(['name' => 'Región De Coquimbo']);
        Region::Create(['name' => 'Región De Valparaíso']);
        Region::Create(['name' => 'Región Metropolitana de Santiago']);
        Region::Create(['name' => 'Región Del Libertador Gral. B. OHiggins']);
        Region::Create(['name' => 'Región Del Maule']);
        Region::Create(['name' => 'Región De Ñuble']);
        Region::Create(['name' => 'Región Del Bíobío']);
        Region::Create(['name' => 'Región De La Araucanía']);
        Region::Create(['name' => 'Región De Los Ríos']);
        Region::Create(['name' => 'Región De Los Lagos']);
        Region::Create(['name' => 'Región De Aysén del General Carlos Ibañez del Campo']);
        Region::Create(['name' => 'Región De Magallanes y de la Antártica Chilena']);
        Region::Create(['name' => 'Región De Arica Parinacota']);
    }
}
