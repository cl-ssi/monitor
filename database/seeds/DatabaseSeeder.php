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
        $this->call(PermissionSeeder::class);
        $this->call(LaboratorySeeder::class);
        $this->call(UserSeeder::class);
        $this->call(PatientSeeder::class);
        $this->call(SuspectCaseSeeder::class);    
    }
}
