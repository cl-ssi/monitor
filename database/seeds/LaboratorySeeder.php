<?php

use Illuminate\Database\Seeder;
use App\Laboratory;

class LaboratorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lab = new Laboratory();
        $lab->name = 'HETG';
        $lab->external = 0;
        $lab->save();

        $lab = new Laboratory();
        $lab->name = 'UNAP';
        $lab->external = 0;
        $lab->save();

        $lab = new Laboratory();
        $lab->name = 'Hospital Lucio Córdova';
        $lab->external = 1;
        $lab->save();
    }
}
