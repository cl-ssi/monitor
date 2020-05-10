<?php

use Illuminate\Database\Seeder;

class VentilatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Ventilator::create(['total'=>0, 'total_real'=>0,'no_covid'=>0]);
    }
}
