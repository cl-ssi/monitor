<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permission = Permission::create(['name' => 'Admin']);
        $permission = Permission::create(['name' => 'Patient: create']);
        $permission = Permission::create(['name' => 'Patient: edit']);
        $permission = Permission::create(['name' => 'Patient: list']);
        $permission = Permission::create(['name' => 'Patient: delete']);
        $permission = Permission::create(['name' => 'SuspectCase: create']);
        $permission = Permission::create(['name' => 'SuspectCase: edit']);
        $permission = Permission::create(['name' => 'SuspectCase: list']);
        $permission = Permission::create(['name' => 'SuspectCase: delete']);
        $permission = Permission::create(['name' => 'Demographic: edit']);
    }
}
