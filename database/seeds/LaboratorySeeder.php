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
        $lab->alias = 'HETG';
        $lab->external = 0;
        $lab->minsal_ws = 1;
        $lab->token_ws = "AK026-88QV-000QAQQCI-000000B8EJTK";
        $lab->pdf_generate = 0;
        $lab->cod_deis = "102100";
        $lab->commune_id = 5;
        $lab->save();

        $lab = new Laboratory();
        $lab->name = 'UNAP';
        $lab->alias = 'UNAP';
        $lab->external = 0;
        $lab->minsal_ws = 1;
        $lab->token_ws = "AK026-88QV-000QAQQCI-000000B8EJTK";
        $lab->pdf_generate = 1;
        $lab->cod_deis = "102100";
        $lab->commune_id = 5;
        $lab->save();

        $lab = new Laboratory();
        $lab->name = 'Hospital Lucio Córdova';
        $lab->alias = 'Hospital Lucio Córdova';
        $lab->external = 1;
        $lab->minsal_ws = 0;
        $lab->token_ws = NULL;
        $lab->pdf_generate = 0;
        $lab->cod_deis = NULL;
        $lab->commune_id = 119;
        $lab->save();
    }
}
