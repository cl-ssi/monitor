<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Rrhh\OrganizationalUnit;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ouTIC = OrganizationalUnit::Where('name','Unidad de Informática y Tecnología')->first();
        $ouDPCR = OrganizationalUnit::Where('name','Departamento de Planificación y Control de Redes')->first();

        $user = new User();
        $user->id = 12345678;
        $user->dv = 9;
        $user->name = "Administrador";
        $user->fathers_family = "Paterno";
        $user->mothers_family = "Materno";
        $user->password = bcrypt('admin');
        $user->position = "Administrator";
        $user->save();
        $user->assignRole('god', 'dev','RRHH: admin');

        $user = new User();
        $user->id = 15287582;
        $user->dv = 7;
        $user->name = "Alvaro";
        $user->fathers_family = "Torres";
        $user->mothers_family = "Fuchslocher";
        $user->email = "alvaro.torres@redsalud.gob.cl";
        $user->password = bcrypt('admin');
        $user->position = "Profesional SIDRA";
        $user->organizationalUnit()->associate($ouTIC);
        $user->save();
        $user->assignRole('god', 'dev');

        $user = User::Create(['id'=>10278387, 'dv'=>5, 'name'=>'José', 'fathers_family'=>'Donoso', 'mothers_family' => 'Carrera',
            'email'=>'jose.donosoc@redsalud.gob.cl','password'=>bcrypt('10278387'), 'position'=>'Jefe', 'organizational_unit_id'=>$ouDPCR->id]);
        $user->assignRole('dev');

        $user = User::Create(['id'=>14107361, 'dv'=>3, 'name'=>'Pamela', 'fathers_family'=>'Villagrán', 'mothers_family' => 'Alvarez',
            'email'=>'pamela.villagran@redsalud.gob.cl','password'=>bcrypt('14107361'), 'position'=>'Administrativa', 'organizational_unit_id'=>$ouDPCR->id]);
        $user->assignRole('dev');

        $user = User::Create(['id'=>16966444, 'dv'=>7, 'name'=>'Jorge', 'fathers_family'=>'Miranda', 'mothers_family' => 'Lopez',
            'email'=>'jorge.mirandal@redsalud.gob.cl','password'=>bcrypt('16966444'), 'position'=>'Profesional SIDRA', 'organizational_unit_id'=>$ouTIC->id]);
        $user->assignRole('god','dev');

        $user = User::Create(['id'=>15924400, 'dv'=>8, 'name'=>'Cristian', 'fathers_family'=>'Carpio', 'mothers_family' => 'Diaz',
            'email'=>'cristian.carpio@redsalud.gob.cl','password'=>bcrypt('15924400'), 'position'=>'Profesional SIDRA', 'organizational_unit_id'=>$ouTIC->id]);
        $user->assignRole('dev');
        
    }
}
