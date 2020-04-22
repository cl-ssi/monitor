<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permission = Permission::create(['name' => 'Admin']);
        $permission = Permission::create(['name' => 'Developer']);
        $permission = Permission::create(['name' => 'Patient: create']);
        $permission = Permission::create(['name' => 'Patient: edit']);
        $permission = Permission::create(['name' => 'Patient: list']);
        $permission = Permission::create(['name' => 'Patient: delete']);
        $permission = Permission::create(['name' => 'Patient: georeferencing']);
        $permission = Permission::create(['name' => 'Patient: epidemiologist']);
        $permission = Permission::create(['name' => 'Demographic: edit']);
        $permission = Permission::create(['name' => 'SuspectCase: create']);
        $permission = Permission::create(['name' => 'SuspectCase: edit']);
        $permission = Permission::create(['name' => 'SuspectCase: list']);
        $permission = Permission::create(['name' => 'SuspectCase: delete']);
        $permission = Permission::create(['name' => 'SuspectCase: admission']);
        $permission = Permission::create(['name' => 'SuspectCase: tecnologo']);
        $permission = Permission::create(['name' => 'SuspectCase: seremi']);
        $permission = Permission::create(['name' => 'Report']);
        $permission = Permission::create(['name' => 'Epp: list']);
        $permission = Permission::create(['name' => 'SanitaryResidence: user']);
        $permission = Permission::create(['name' => 'SanitaryResidence: admin']);
        $permission = Permission::create(['name' => 'File_report: viewer']);
        $permission = Permission::create(['name' => 'Historical Report']);

    }
}
