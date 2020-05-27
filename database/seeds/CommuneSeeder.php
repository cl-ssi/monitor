<?php

use App\Commune;
use Illuminate\Database\Seeder;

class CommuneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    /* id = 1 */ $commune = Commune::Create(['name'=>'Alto Hospicio','code_deis'=>1107]);
    /* id = 2 */ $commune = Commune::Create(['name'=>'Camiña','code_deis'=>1402]);
    /* id = 3 */ $commune = Commune::Create(['name'=>'Colchane','code_deis'=>1403]);
    /* id = 4 */ $commune = Commune::Create(['name'=>'Huara','code_deis'=>1404]);
    /* id = 5 */ $commune = Commune::Create(['name'=>'Iquique','code_deis'=>1101]);
    /* id = 6 */ $commune = Commune::Create(['name'=>'Pica','code_deis'=>1405]);
    /* id = 7 */ $commune = Commune::Create(['name'=>'Pozo Almonte','code_deis'=>1401]);
    }
}
