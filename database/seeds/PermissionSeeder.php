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
        $permission = Permission::create([
            'name' => 'Admin',
            'description' => 'Administrador del sistema (Permiso muy poderoso)',
            'category' => 'Administracion'
        ]);
        $permission = Permission::create([
            'name' => 'Developer',
            'description' => 'Desarrollador (Solamente los Informáticos del Sistema Deberían tenerlo)',
            'category' => 'Administracion'
        ]);

        $permission = Permission::create([
            'name' => 'Patient: create',
            'description' => 'Permite crear un paciente (Funcionarios que hagan seguimiento principalmente)',
            'category' => 'Paciente'
        ]);
        $permission = Permission::create([
            'name' => 'Patient: delete',
            'description' => 'Permite eliminar un paciente (Sólo administradores)',
            'category' => 'Paciente'
        ]);
        $permission = Permission::create([
            'name' => 'Patient: demographic edit',
            'description' => 'Permite editar datos demográficos de un paciente (Para cualquiera que pueda editar los datos de un paciente)',
            'category' => 'Paciente'
        ]);
        $permission = Permission::create([
            'name' => 'Patient: edit',
            'description' => 'Permite editar un paciente (Para cualquiera que pueda editar los datos de un paciente)',
            'category' => 'Paciente'
        ]);
        $permission = Permission::create([
            'name' => 'Patient: epidemiologist',
            'description' => 'Si uso por ahora',
            'category' => 'Paciente'
        ]);
        $permission = Permission::create([
            'name' => 'Patient: georeferencing',
            'description' => 'Permite visualizar georeferencia de pacientes positivos (global, de todo el sistema)',
            'category' => 'Paciente'
        ]);
        $permission = Permission::create([
            'name' => 'Patient: list',
            'description' => 'Permite visualizar listado de pacientes',
            'category' => 'Paciente'
        ]);
        
        $permission = Permission::create([
            'name' => 'Patient: fusion',
            'description' => 'Permite fusionar pacientes',
            'category' => 'Paciente'
        ]);

        $permission = Permission::create([
            'name' => 'Patient: show',
            'description' => 'Permite ver datos de los pacientes sin poder modificar datos',
            'category' => 'Paciente'
        ]);


        $permission = Permission::create([
            'name' => 'SanitaryResidence: admin',
            'description' => 'Administrador Residencia Sanitaria',
            'category' => 'Residencia Sanitaria'
        ]);

        $permission = Permission::create([
            'name' => 'SanitaryResidence: admission',
            'description' => 'Usuario de SEREMI que autoriza ingreso a R.S.',
            'category' => 'Residencia Sanitaria'
        ]);

        $permission = Permission::create([
            'name' => 'SanitaryResidence: survey',
            'description' => 'Usuario que realiza encuesta para ver si Califica a R.S. en terreno (patient edit y patient list)',
            'category' => 'Residencia Sanitaria'
        ]);

        $permission = Permission::create([
            'name' => 'SanitaryResidence: return booking',
            'description' => 'Usuario que puede eliminar un egreso, y paciente se retorna a su booking',
            'category' => 'Residencia Sanitaria'
        ]);

        $permission = Permission::create([
            'name' => 'SanitaryResidence: user',
            'description' => 'Permite al usuario registrar en R.S.',
            'category' => 'Residencia Sanitaria'
        ]);


        $permission = Permission::create([
            'name' => 'SanitaryResidence: view',
            'description' => 'Permite al usuario ver (no registrar) en R.S.',
            'category' => 'Residencia Sanitaria'
        ]);

        $permission = Permission::create([
            'name' => 'SuspectCase: admission',
            'description' => 'Permite crear muestra (tomadores de muestra)',
            'category' => 'Caso Sospechoso'
        ]);

        $permission = Permission::create([
           'name' => 'SuspectCase: create',
           'description' => '(No utilizar)',
           'category' => 'Caso Sospechoso'
        ]);

        $permission = Permission::create([
            'name' => 'SuspectCase: delete',
            'description' => 'Permite eliminar muestra (sólo para administradores)',
            'category' => 'Caso Sospechoso'
        ]);

        $permission = Permission::create([
            'name' => 'SuspectCase: edit',
            'description' => 'Permite editar muestra (sólo para administradores)',
            'category' => 'Caso Sospechoso'
        ]);

        $permission = Permission::create([
            'name' => 'SuspectCase: file delete',
            'description' => 'Permite eliminar el PDF de resultado de un examen (sólo para administradores)',
            'category' => 'Caso Sospechoso'
        ]);

        $permission = Permission::create([
            'name' => 'SuspectCase: list',
            'description' => 'Lista todos los exámenes por laboratorio, excluye aquellos que no han sido recepcionados',
            'category' => 'Caso Sospechoso'
        ]);

        $permission = Permission::create([
            'name' => 'SuspectCase: reception',
            'description' => 'Permite recepcionar muestra (para tecnólogos)',
            'category' => 'Caso Sospechoso'
        ]);

        $permission = Permission::create([
            'name' => 'SuspectCase: tecnologo',
            'description' => 'Permite agregar resultado de exámenes (para tecnólogos)',
            'category' => 'Caso Sospechoso'
        ]);

        $permission = Permission::create([
            'name' => 'SuspectCase: tecnologo edit',
            'description' => 'Permite editar el resultado del exámen (para tecnólogos administradores)',
            'category' => 'Caso Sospechoso'
        ]);

        $permission = Permission::create([
            'name' => 'SuspectCase: own',
            'description' => 'Lista todos los exámenes ingresados por un usuario y adicionalmente los que que han sido ingresados en el mismo establecimiento',
            'category' => 'Caso Sospechoso'
        ]);

        $permission = Permission::create([
            'name' => 'SuspectCase: bulk load',
            'description' => 'Permite acceso al formulario de carga masiva de pacientes, exámenes y resultados',
            'category' => 'Caso Sospechoso'
        ]);

        $permission = Permission::create([
            'name' => 'SuspectCase: import results',
            'description' => 'Permite acceso al formulario importar resultados de exámenes',
            'category' => 'Caso Sospechoso'
        ]);

        $permission = Permission::create([
            'name' => 'Lab: menu',
            'category' => 'Laboratorio'
        ]);

        $permission = Permission::create([
            'name' => 'Report: positives',
            'description' => 'Reporte de positivos (Global del sistema)',
            'category' => 'Reporte'
        ]);

        $permission = Permission::create([
            'name' => 'Report: other',
            'description' => 'Reporte de positivos antiguo (en desuso)',
            'category' => 'Reporte'
        ]);

        $permission = Permission::create([
            'name' => 'Report: historical',
            'description' => 'Reporte de positivos historico, con corte a las 21:00 (depende de CRON o tareas programadas)',
            'category' => 'Reporte'
        ]);

        $permission = Permission::create([
            'name' => 'Report: exams with result',
            'description' => 'Reporte de exámenes con resultados',
            'category' => 'Reporte'
        ]);

        $permission = Permission::create([
            'name' => 'Report: gestants',
            'description' => 'Reporte de pacientes Gestantes.',
            'category' => 'Reporte'
        ]);

        $permission = Permission::create([
            'name' => 'Report: residences',
            'description' => 'Reporte de "Consolidado de Booking" Total Ocupacional-Habitacional por R.S.',
            'category' => 'Reporte'
        ]);

        $permission = Permission::create([
            'name' => 'Report: positives by range',
            'description' => 'Reporte Casos positivos por rango de fechas',
            'category' => 'Reporte'
        ]);

        $permission = Permission::create([
            'name' => 'Report: hospitalized',
            'description' => 'Reporte de Listado de hospitalizados',
            'category' => 'Reporte'
        ]);

        $permission = Permission::create([
            'name' => 'Report: commune',
            'description' => 'Reporte de positivos de mi comuna',
            'category' => 'Reporte'
        ]);

        $permission = Permission::create([
            'name' => 'Report: deceased',
            'description' => 'Reporte de pacientes fallecidos',
            'category' => 'Reporte'
        ]);

        $permission = Permission::create([
            'name' => 'Report: user performance',
            'description' => 'Reporte de eventos registrados por usuario',
            'category' => 'Reporte'
        ]);

        $permission = Permission::create([
            'name' => 'Report: more than two days',
            'description' => 'Reporte de casos pendientes mayor a 48 horas',
            'category' => 'Reporte'
        ]);

        $permission = Permission::create([
            'name' => 'Epp: list',
            'description' => 'Movimientos de EPP',
            'category' => 'EPP'
        ]);

        $permission = Permission::create([
            'name' => 'Rapid Test: list',
            'description' => 'Permite listar Test Rápido',
            'category' => 'Test Rápido'
        ]);

        $permission = Permission::create([
            'name' => 'Rapid Test: create',
            'description' => 'Permite crear Test Rápido ',
            'category' => 'Test Rápido'
        ]);

        $permission = Permission::create([
            'name' => 'Rapid Test: edit',
            'description' => 'Permite editar Test Rápido ',
            'category' => 'Test Rápido'
        ]);

        $permission = Permission::create([
            'name' => 'Rapid Test: delete',
            'description' => 'Pemite eliminar un test rápido',
            'category' => 'Test Rápido'
        ]);

        $permission = Permission::create([
            'name' => 'SocialTracing: seremi',
            'description' => 'Permite acceder a los solicitudes de licencia SEREMI',
            'category' => 'Trazabilidad'
        ]);
        $permission = Permission::create([
            'name' => 'SocialTracing: aps',
            'description' => 'Permite acceder a los solicitudes de licencia APS',
            'category' => 'Trazabilidad'
        ]);

        $permission = Permission::create([
            'name' => 'Geo: communes',
            'description' => 'Mapa de casos en seguimiento por comuna asociada a usuario',
            'category' => 'Geo'
        ]);

        $permission = Permission::create([
            'name' => 'Geo: establishments',
            'description' => 'Mapa de casos en seguimiento por establecimiento asociado a usuario',
            'category' => 'Geo'
        ]);

        $permission = Permission::create([
            'name' => 'Report: hospitalized commune',
            'description' => 'Reporte de Listado de hospitalizados por comunas asociadas a los establecimientos del usuario',
            'category' => 'Reporte'
        ]);

        $permission = Permission::create([
            'name' => 'Patient: tracing',
            'description' => 'Seguimiento a Paciente',
            'category' => 'Paciente'
        ]);

        $permission = Permission::create([
            'name' => 'Patient: tracing old',
            'description' => 'Antiguo permiso de Tracing, ya no se utiliza',
            'category' => 'Paciente'
        ]);

        $permission = Permission::create([
            'name' => 'Report: positive average by commune',
            'description' => 'Reporte de positivos por comuna',
            'category' => 'Reporte'
        ]);

        $permission = Permission::create([
            'name' => 'Report: cases with barcodes',
            'description' => 'Listado de muestras por establecimiento y fecha de muestra con código de barra',
            'category' => 'Reporte'
        ]);

        $permission = Permission::create([
            'name' => 'Report: rapid test',
            'description' => 'Listado de Pacientes con Test Rápido',
            'category' => 'Reporte'
        ]);

        $permission = Permission::create([
            'name' => 'NotContacted: create',
            'description' => 'Crear nuevo paciente no contactado',
            'category' => 'Contacto'
        ]);

        $permission = Permission::create([
            'name' => 'NotContacted: list',
            'description' => 'Listar pacientes no contactados',
            'category' => 'Contacto'
        ]);

        $permission = Permission::create([
            'name' => 'NotContacted: show all',
            'description' => 'Listar pacientes no contactados independiente de establecimiento asignado',
            'category' => 'Contacto'
        ]);

        $permission = Permission::create([
            'name' => 'NotContacted: delete',
            'description' => 'Borrar paciente no contactado',
            'category' => 'Contacto'
        ]);

        $permission = Permission::create([
            'name' => 'SuspectCase: reception with barcode',
            'description' => 'Habilita botón para recepcionar mediante código de barras',
            'category' => 'Caso Sospechoso'
        ]);

        $permission = Permission::create([
            'name' => 'SuspectCase: bulk load PNTM',
            'description' => 'Carga masiva desde reporte PNTM',
            'category' => 'Caso Sospechoso'
        ]);

        $permission = Permission::create([
            'name' => 'SuspectCase: sequencing',
            'description' => 'Módulo de secuenciación',
            'category' => 'Caso Sospechoso'
        ]);

        $permission = Permission::create([
            'name' => 'SuspectCase: sequencing ISP',
            'description' => 'Añade resultado ISP y Sospecha Local',
            'category' => 'Caso Sospechoso'
        ]);


        $permission = Permission::create([
            'name' => 'SuspectCase: origin',
            'description' => 'Mantenedor de los orígenes de muestra',
            'category' => 'Caso Sospechoso'
        ]);

        $permission = Permission::create([
            'name' => 'Redirection: https://esmeralda.saludtarapaca.org/',
            'description' => 'Sale por pantalla un mensaje que deben ingresar al nuevo link',
            'category' => 'Otros'
        ]);


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
