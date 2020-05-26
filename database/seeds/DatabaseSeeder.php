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
        $this->call(LaboratorySeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(UserSeeder::class);
        if(env('APP_ENV') == 'testing') {
            $this->call(PatientSeeder::class);
            $this->call(DemographicSeeder::class);
            $this->call(SuspectCaseSeeder::class);
        }
        $this->call(ResidenceSeeder::class);
        $this->call(RoomSeeder::class);
        $this->call(CommuneSeeder::class);
        $this->call(EstablishmentSeeder::class);
        $this->call(VentilatorSeeder::class);
    }
}
