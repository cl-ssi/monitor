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
        $permission = Permission::create(['name' => 'Patient: delete']);
        $permission = Permission::create(['name' => 'Patient: demographic edit']);
        $permission = Permission::create(['name' => 'Patient: edit']);
        $permission = Permission::create(['name' => 'Patient: epidemiologist']);
        $permission = Permission::create(['name' => 'Patient: georeferencing']);
        $permission = Permission::create(['name' => 'Patient: list']);
        $permission = Permission::create(['name' => 'Patient: tracing']);

        $permission = Permission::create(['name' => 'SanitaryResidence: user']);
        $permission = Permission::create(['name' => 'SanitaryResidence: admin']);

        $permission = Permission::create(['name' => 'SuspectCase: admission']);
        $permission = Permission::create(['name' => 'SuspectCase: create']);
        $permission = Permission::create(['name' => 'SuspectCase: delete']);
        $permission = Permission::create(['name' => 'SuspectCase: edit']);
        $permission = Permission::create(['name' => 'SuspectCase: file delete']);
        $permission = Permission::create(['name' => 'SuspectCase: list']);
        $permission = Permission::create(['name' => 'SuspectCase: reception']);
        $permission = Permission::create(['name' => 'SuspectCase: tecnologo']);
        $permission = Permission::create(['name' => 'SuspectCase: tecnologo edit']);
        $permission = Permission::create(['name' => 'SuspectCase: own']);

        $permission = Permission::create(['name' => 'Lab: menu']);

        $permission = Permission::create(['name' => 'Report: positives']);
        $permission = Permission::create(['name' => 'Report: other']);
        $permission = Permission::create(['name' => 'Report: historical']);
        $permission = Permission::create(['name' => 'Report: exams with result']);
        $permission = Permission::create(['name' => 'Report: gestants']);
        $permission = Permission::create(['name' => 'Report: residences']);
        $permission = Permission::create(['name' => 'Report: positives by range']);
        
        $permission = Permission::create(['name' => 'Epp: list']);

        $permission = Permission::create(['name' => 'Inmuno Test: list']);
        $permission = Permission::create(['name' => 'Inmuno Test: create']);
        $permission = Permission::create(['name' => 'Inmuno Test: edit']);




/*
        $users = User::all();
        foreach($users as $user) {
            if($user->can('File_report: viewer')){
                $user->revokePermissionTo('Demographic: edit');
                $user->givePermissionTo('Patient: demographic edit');
            }
        }
*/
        //$user->can('edit articles');
        //$user->givePermissionTo('edit articles');
        //$user->revokePermissionTo('edit articles');
    }
}
