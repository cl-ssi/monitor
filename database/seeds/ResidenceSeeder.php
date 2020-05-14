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
            ['id' => 2, 'name' => 'Colegio Universitario UNAP' , 'address' => 'Zegers #426', 'telephone'=> '' ],
            ['id' => 3, 'name' => 'Hotel Urbano' , 'address' => 'Patricio Lynch #679', 'telephone'=> '572764012' ],

        ];

        foreach ($items as $item) {
            \App\SanitaryResidence\Residence::create($item);
        }
    }
}
