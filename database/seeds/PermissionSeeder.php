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
        $permission = Permission::create(['name' => 'Admin', 'description' => 'Administrador del sistema']);
        $permission = Permission::create(['name' => 'Developer', 'description' => 'Desarrollador (Solamente los Informáticos del Sistema Deberían tenerlo)']);

        $permission = Permission::create(['name' => 'Patient: create', 'description' => 'Permite crear un paciente']);
        $permission = Permission::create(['name' => 'Patient: delete', 'description' => 'Permite eliminar un paciente']);
        $permission = Permission::create(['name' => 'Patient: demographic edit', 'description' => 'Permite editar datos demográficos de un paciente']);
        $permission = Permission::create(['name' => 'Patient: edit', 'description' => 'Permite editar un paciente']);
        $permission = Permission::create(['name' => 'Patient: epidemiologist', 'description' => 'Permite crear un paciente']);
        $permission = Permission::create(['name' => 'Patient: georeferencing', 'description' => 'Permite visualizar georeferencia de pacientes positivos']);
        $permission = Permission::create(['name' => 'Patient: list', 'description' => 'Permite visualizar listado de pacientes']);


        $permission = Permission::create(['name' => 'SanitaryResidence: admin', 'description' => 'Administrador Residencia Sanitaria']);
        $permission = Permission::create(['name' => 'SanitaryResidence: admission', 'description' => 'Usuario de SEREMI que autoriza ingreso a R.S.']);
        $permission = Permission::create(['name' => 'SanitaryResidence: survey', 'description' => 'Usuario que realiza encuesta para ver si Califica a R.S. en terreno (patient edit y patient list)']);
        $permission = Permission::create(['name' => 'SanitaryResidence: user', 'description' => 'Permite al usuario registrar en R.S.']);

        $permission = Permission::create(['name' => 'Basket:', 'description' => 'Usuarios de modulo intendencia']);

        $permission = Permission::create(['name' => 'SuspectCase: admission', 'description' => 'Permite crear muestra']);
        // $permission = Permission::create(['name' => 'SuspectCase: create']);
        $permission = Permission::create(['name' => 'SuspectCase: delete', 'description' => 'Permite eliminar muestra']);
        $permission = Permission::create(['name' => 'SuspectCase: edit', 'description' => 'Permite editar muestra']);
        $permission = Permission::create(['name' => 'SuspectCase: file delete', 'description' => 'Permite eliminar adjunto de muestra']);
        $permission = Permission::create(['name' => 'SuspectCase: list', 'description' => 'Lista todos los exámenes por laboratorio, excluye aquellos que no han sido recepcionados']);
        $permission = Permission::create(['name' => 'SuspectCase: reception', 'description' => 'Permite recepcionar muestra']);
        $permission = Permission::create(['name' => 'SuspectCase: tecnologo', 'description' => 'Permite ingresar al paciente desde la lista de examenes']);
        $permission = Permission::create(['name' => 'SuspectCase: tecnologo edit', 'description' => 'Permite editar el resultado del exámen']);
        $permission = Permission::create(['name' => 'SuspectCase: own', 'description' => 'Lista todos los exámenes que han sido ingresados en el mismo establecimiento asociado al usuario y adicionalmente los que el ingreso independiente del establecimiento que seleccionó']);//
        $permission = Permission::create(['name' => 'SuspectCase: bulk load', 'description' => 'Permite acceso al formulario de carga masiva']);
        $permission = Permission::create(['name' => 'SuspectCase: import results', 'description' => 'Acceso a vista para importar resultados']);
        $permission = Permission::create(['name' => 'Lab: menu']);

        $permission = Permission::create(['name' => 'Report: positives', 'description' => 'Reporte de positivos']);
        $permission = Permission::create(['name' => 'Report: other', 'description' => '']);//???
        $permission = Permission::create(['name' => 'Report: historical', 'description' => '']);//??
        $permission = Permission::create(['name' => 'Report: exams with result', 'description' => 'Listado de exámenes con resultados']);
        $permission = Permission::create(['name' => 'Report: gestants', 'description' => 'Reporte de pacientes Gestantes.']);
        $permission = Permission::create(['name' => 'Report: residences', 'description' => 'Reporte de "Consolidado de Booking" Total Ocupacional-Habitacional por R.S.']);
        $permission = Permission::create(['name' => 'Report: positives by range', 'description' => 'Reporte Casos positivos por rango de fechas']);
        $permission = Permission::create(['name' => 'Report: hospitalized', 'description' => 'Reporte de Listado de hospitalizados']);
        $permission = Permission::create(['name' => 'Report: commune', 'description' => 'Reporte de mi comuna']);
        $permission = Permission::create(['name' => 'Report: deceased', 'description' => 'Reporte de pacientes fallecidos']);
        $permission = Permission::create(['name' => 'Report: user performance', 'description' => 'Reporte de eventos registrados por usuario']);

        $permission = Permission::create(['name' => 'Epp: list', 'description' => 'Movimientos de EPP']);

        $permission = Permission::create(['name' => 'Inmuno Test: list', 'description' => 'Permite listar Test Rapido Inmunoglobulinas']);
        $permission = Permission::create(['name' => 'Inmuno Test: create', 'description' => 'Permite crear Test Rapido Inmunoglobulinas']);
        $permission = Permission::create(['name' => 'Inmuno Test: edit', 'description' => 'Permite editar Test Rapido Inmunoglobulinas']);

        $permission = Permission::create(['name' => 'SocialTracing: seremi', 'description' => 'Permite acceder a los solicitudes de licencia SEREMI']);
        $permission = Permission::create(['name' => 'SocialTracing: aps', 'description' => 'Permite acceder a los solicitudes de licencia APS']);

        $permission = Permission::create(['name' => 'Geo: communes', 'description' => 'Mapa de caso índice y CAR por comunas, asociada a usuario']);
        $permission = Permission::create(['name' => 'Geo: establishments', 'description' => 'Mapa de caso índice y CAR por establecimiento, asociada a usuario']);

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
