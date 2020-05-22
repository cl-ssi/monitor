<?php

use App\Commune;
use Illuminate\Database\Seeder;

class CommunesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    /* id = 1 */ $commune = Commune::Create(['name'=>'Colchane']);
    /* id = 2 */ $commune = Commune::Create(['name'=>'Huara']);
    /* id = 3 */ $commune = Commune::Create(['name'=>'Camiña']);
    /* id = 4 */ $commune = Commune::Create(['name'=>'Pozo Almonte']);
    /* id = 5 */ $commune = Commune::Create(['name'=>'Pica']);
    /* id = 6 */ $commune = Commune::Create(['name'=>'Iquique']);
    /* id = 7 */ $commune = Commune::Create(['name'=>'Alto Hospicio']);
    }
}
