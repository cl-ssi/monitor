<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use Spatie\Permission\Models\Permission;

class MassPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mass:permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza permisos de forma masiva';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        /** Disable auditing */
        //User::disableAuditing();

        /** Todos los usuarios */
        $users = User::all();

        foreach($users as $user)
        {
            /** Convertimos en mayúscula la primera letra del nombre */
            $user->name = ucwords(mb_strtolower($user->name));

            /** Si no tiene run, le agrego el permiso de redireccionar a esmeralda */
            if(!$user->run) $user->givePermissionTo('Redirection: https://esmeralda.saludtarapaca.org/');

            /** Eliminar permisos que ya no se ocupan */
            if($user->can('Patient: tracing old')) $user->revokePermissionTo('Patient: tracing old');
            if($user->can('Report: positives')) $user->revokePermissionTo('Report: positives');
            if($user->can('Epp: list')) $user->revokePermissionTo('Epp: list');
            if($user->can('Report: other')) $user->revokePermissionTo('Report: other');
            if($user->can('SuspectCase: create')) $user->revokePermissionTo('SuspectCase: create');
            if($user->can('Basket:')) $user->revokePermissionTo('Basket:');
            if($user->can('NotContacted: create')) $user->revokePermissionTo('NotContacted: create');
            if($user->can('NotContacted: delete')) $user->revokePermissionTo('NotContacted: delete');
            if($user->can('NotContacted: list')) $user->revokePermissionTo('NotContacted: list');
            if($user->can('NotContacted: show all')) $user->revokePermissionTo('NotContacted: show all');
            //if($user->can('SuspectCase: bulk load PNTM')) $user->revokePermissionTo('SuspectCase: bulk load PNTM');

            /** Si ya tiene el permiso, entonces no hago nada */
            if(!$user->can('Redirection: https://esmeralda.saludtarapaca.org/'))
            {
                /** Si NO es tecnólogo entonces le seteo la redirección */
                if(!$user->can('Admin') AND !$user->can('SuspectCase: tecnologo') AND !$user->can('SuspectCase: tecnologo edit') AND !$user->can('SuspectCase: sequencing')) 
                {
                    $user->givePermissionTo('Redirection: https://esmeralda.saludtarapaca.org/');
                    echo $user->name."\n";
                }
                
            }
            $user->save();
        }

        // Re-enable auditing
        //User::enableAuditing();

        return 0;
    }
}
