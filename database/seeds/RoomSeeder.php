<?php

use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            ///CARGA DE LOS CUARTOS DEL AGUA LUNA
            ['id' => 1, 'floor' => 1, 'number' => '11', 'residence_id' => '1'],
            ['id' => 2, 'floor' => 1, 'number' => '12', 'residence_id' => '1'],
            ['id' => 3, 'floor' => 2, 'number' => '21', 'residence_id' => '1'],
            ['id' => 4, 'floor' => 2, 'number' => '22', 'residence_id' => '1'],
            ['id' => 5, 'floor' => 2, 'number' => '23', 'residence_id' => '1'],
            ['id' => 6, 'floor' => 2, 'number' => '24', 'residence_id' => '1'],
            ['id' => 7, 'floor' => 2, 'number' => '25', 'residence_id' => '1'],
            ['id' => 8, 'floor' => 2, 'number' => '26', 'residence_id' => '1'],
            ['id' => 9, 'floor' => 2, 'number' => '27', 'residence_id' => '1'],
            ['id' => 10, 'floor' => 3, 'number' => '31', 'residence_id' => '1'],
            ['id' => 11, 'floor' => 3, 'number' => '32', 'residence_id' => '1'],
            ['id' => 12, 'floor' => 3, 'number' => '33', 'residence_id' => '1'],
            ['id' => 13, 'floor' => 3, 'number' => '34', 'residence_id' => '1'],
            ['id' => 14, 'floor' => 3, 'number' => '35', 'residence_id' => '1'],
            ['id' => 15, 'floor' => 3, 'number' => '36', 'residence_id' => '1'],
            ['id' => 16, 'floor' => 3, 'number' => '37', 'residence_id' => '1'],
            ['id' => 17, 'floor' => 4, 'number' => '41', 'residence_id' => '1'],
            ['id' => 18, 'floor' => 5, 'number' => '51', 'residence_id' => '1'],
            ['id' => 19, 'floor' => 5, 'number' => '52', 'residence_id' => '1'],


            ///CARGA DE LOS CUARTOS DEL UNAP
            ['id' => 20, 'floor' => 1, 'number' => '1', 'residence_id' => '2'],
            ['id' => 21, 'floor' => 1, 'number' => '2', 'residence_id' => '2'],
            ['id' => 22, 'floor' => 1, 'number' => '3', 'residence_id' => '2'],
            ['id' => 23, 'floor' => 1, 'number' => '4', 'residence_id' => '2'],
            ['id' => 24, 'floor' => 1, 'number' => '5', 'residence_id' => '2'],
            ['id' => 25, 'floor' => 1, 'number' => '6', 'residence_id' => '2'],
            ['id' => 26, 'floor' => 1, 'number' => '7', 'residence_id' => '2'],
            ['id' => 27, 'floor' => 1, 'number' => '8', 'residence_id' => '2'],
            ['id' => 28, 'floor' => 1, 'number' => '9', 'residence_id' => '2'],
            ['id' => 29, 'floor' => 1, 'number' => '10', 'residence_id' => '2'],
            ['id' => 30, 'floor' => 1, 'number' => '11', 'residence_id' => '2'],
            ['id' => 31, 'floor' => 1, 'number' => '12', 'residence_id' => '2'],

        ];

        foreach ($items as $item) {
            \App\SanitaryResidence\Room::create($item);
        }
    }
}
