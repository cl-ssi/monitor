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

            ['id' => 1, 'name' => 'Hotel Agua Luna' , 'address' => 'Caleta Rio Seco 2152', 'telephone'=> '572449287', 'width'=> '172', 'height'=> '172'],
            ['id' => 2, 'name' => 'Colegio Universitario UNAP' , 'address' => 'Zegers #426', 'telephone'=> '', 'width'=> '200', 'height'=> '600' ],
            ['id' => 3, 'name' => 'Hotel Urbano' , 'address' => 'Patricio Lynch #679', 'telephone'=> '572764012', 'width'=> '172', 'height'=> '172' ],

        ];

        foreach ($items as $item) {
            \App\SanitaryResidence\Residence::create($item);
        }
    }
}
