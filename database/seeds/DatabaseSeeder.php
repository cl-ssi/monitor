<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        
        
        $this->call(PatientsTableSeeder::class);
        $this->call(SuspectCasesTableSeeder::class);
        $this->call(HotelSeeder::class);
        $this->call(RoomSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(LaboratorySeeder::class);
        $this->call(UserSeeder::class);
        
    }
}
