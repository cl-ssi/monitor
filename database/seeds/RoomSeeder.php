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
            ['id' => 1, 'floor' => 1, 'number' => '11', 'hotel_id' => '1'],
            ['id' => 2, 'floor' => 1, 'number' => '12', 'hotel_id' => '1'],
            ['id' => 3, 'floor' => 2, 'number' => '21', 'hotel_id' => '1'],
            ['id' => 4, 'floor' => 2, 'number' => '22', 'hotel_id' => '1'],
            ['id' => 5, 'floor' => 2, 'number' => '23', 'hotel_id' => '1'],
            ['id' => 6, 'floor' => 2, 'number' => '24', 'hotel_id' => '1'],
            ['id' => 7, 'floor' => 2, 'number' => '25', 'hotel_id' => '1'],
            ['id' => 8, 'floor' => 2, 'number' => '26', 'hotel_id' => '1'],
            ['id' => 9, 'floor' => 2, 'number' => '27', 'hotel_id' => '1'],
            ['id' => 10, 'floor' => 3, 'number' => '31', 'hotel_id' => '1'],
            ['id' => 11, 'floor' => 3, 'number' => '32', 'hotel_id' => '1'],
            ['id' => 12, 'floor' => 3, 'number' => '33', 'hotel_id' => '1'],
            ['id' => 13, 'floor' => 3, 'number' => '34', 'hotel_id' => '1'],
            ['id' => 14, 'floor' => 3, 'number' => '35', 'hotel_id' => '1'],
            ['id' => 15, 'floor' => 3, 'number' => '36', 'hotel_id' => '1'],
            ['id' => 16, 'floor' => 3, 'number' => '37', 'hotel_id' => '1'],
            ['id' => 17, 'floor' => 4, 'number' => '41', 'hotel_id' => '1'],
            ['id' => 18, 'floor' => 5, 'number' => '51', 'hotel_id' => '1'],
            ['id' => 19, 'floor' => 5, 'number' => '52', 'hotel_id' => '1'],

        ];

        foreach ($items as $item) {
            \App\SanitaryHotel\Room::create($item);
        }
    }
}
