<?php

use Illuminate\Database\Seeder;
use App\User;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->run = "15637637";
        $user->dv = 'K';
        $user->name = "Administrador";
        $user->email = "admin@ssdr.gob.cl";
        $user->password = bcrypt('admin');
        $user->save();
        $user->givePermissionTo(Permission::all());
    }
}
