<?php

use Illuminate\Database\Seeder;

class ResidenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $items = [

            ['id' => 1, 'name' => 'Hotel Agua Luna' , 'address' => 'Caleta Rio Seco 2152', 'telephone'=> '572449287' ],                       

        ];

        foreach ($items as $item) {
            \App\SanitaryResidence\Residence::create($item);
        }
    }
}
