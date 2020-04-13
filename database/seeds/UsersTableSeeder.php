<?php

use Illuminate\Database\Seeder;
use App\User;
use Spatie\Permission\Models\Permission;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->name = "Administrador";
        $user->email = "sistemas.ssi@redsalud.gob.cl";
        $user->password = bcrypt('admin');
        $user->save();
        $user->givePermissionTo(Permission::all());

        $user = new User();
        $user->name = "Sandra Riquelme";
        $user->email = "sandra.riquelmemolina@gmail.com";
        $user->password = bcrypt('salud123');
        $user->save();
        $user->givePermissionTo(Permission::all());


        $user = new User();
        $user->name = "Liumara Davalos";
        $user->email = "liudamaldonado@gmail.com";
        $user->password = bcrypt('salud123');
        $user->save();
        $user->givePermissionTo(Permission::all());
    }
}
