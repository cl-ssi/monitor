<?php

use Illuminate\Database\Seeder;

class TestingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        //$this->makeData(10,2);
    }

    /**
     * Crea n Patient, por cada Patient crea 1 Demographic
     * x% de los Patient, tendrán 1 SuspectCases
     * y% de los Patient, tendrán 2 SuspectCases
     * z% de los Patient, tendrán 3 SuspectCases
     * @return integer = Total de los Registros creados
     */
    public function create($n, $x, $y, $z){
      $this->makeData((int) round($n*($x/100)),1);
      $this->makeData((int) round($n*($y/100)),2);
      $this->makeData((int) round($n*($z/100)),3);
      return $n;
    }

// Function makeData: Crea $n_pat Patient, 1 Demographic por Patient y $n_cas SuspectCases por Patient
    private function makeData($n_pat, $n_cas){
      factory(App\Patient::class, $n_pat)->create()->
        each(function($patient)use($n_cas){
          $patient->demographic()->save(factory(App\Demographic::class)->make());
          $patient->suspectCases()->createMany(factory(App\SuspectCase::class,$n_cas)->make()->toArray());
        });
    }

}
