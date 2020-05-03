<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ReportBackup as ReportBackup2;
use App\SuspectCase;
use App\Patient;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Report:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tarea que genera backup de reporte.';

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
     * @return mixed
     */
    public function handle()
    {
      // $cases = SuspectCase::with('patient')->with('patient.demographic')->get();

      $patients = Patient::whereHas('suspectCases', function ($q) { $q->where('pscr_sars_cov_2','positive'); })->with('suspectCases')->get();
      $patients = $patients->whereNotIn('demographic.region',
                  [
                  'Arica y Parinacota',
                  'Antofagasta',
                  'Atacama',
                  'Coquimbo',
                  'Valparaíso',
                  'Región del Libertador Gral. Bernardo O’Higgins',
                  'Región del Maule',
                  'Región del Biobío',
                  'Región de la Araucanía',
                  'Región de Los Ríos',
                  'Región de Los Lagos',
                  'Región Aisén del Gral. Carlos Ibáñez del Campo',
                  'Región de Magallanes y de la Antártica Chilena',
                  'Región Metropolitana de Santiago',
                  'Región de Ñuble']);


      $cases = SuspectCase::All();
      $cases = $cases->where('discharge_test', '<>', 1)->whereNotIn('patient.demographic.region',
                      [
                      'Arica y Parinacota',
                      'Antofagasta',
                      'Atacama',
                      'Coquimbo',
                      'Valparaíso',
                      'Región del Libertador Gral. Bernardo O’Higgins',
                      'Región del Maule',
                      'Región del Biobío',
                      'Región de la Araucanía',
                      'Región de Los Ríos',
                      'Región de Los Lagos',
                      'Región Aisén del Gral. Carlos Ibáñez del Campo',
                      'Región de Magallanes y de la Antártica Chilena',
                      'Región Metropolitana de Santiago',
                      'Región de Ñuble']);
      $cases_other_region = SuspectCase::All();
      $cases_other_region = $cases_other_region->whereIn('patient.demographic.region',
                      [
                      'Arica y Parinacota',
                      'Antofagasta',
                      'Atacama',
                      'Coquimbo',
                      'Valparaíso',
                      'Región del Libertador Gral. Bernardo O’Higgins',
                      'Región del Maule',
                      'Región del Biobío',
                      'Región de la Araucanía',
                      'Región de Los Ríos',
                      'Región de Los Lagos',
                      'Región Aisén del Gral. Carlos Ibáñez del Campo',
                      'Región de Magallanes y de la Antártica Chilena',
                      'Región Metropolitana de Santiago',
                      'Región de Ñuble']);
      //
      // $totales_dia = DB::table('suspect_cases')
      //     ->select('sample_at', DB::raw('count(*) as total'))
      //     ->where('pscr_sars_cov_2', 'positive')
      //     ->groupBy('sample_at')
      //     ->orderBy('sample_at')
      //     ->get();


      // $begin = new \DateTime($totales_dia->first()->sample_at);
      // $end   = new \DateTime($totales_dia->last()->sample_at);

      $begin = SuspectCase::where('pscr_sars_cov_2','positive')->orderBy('sample_at')->first()->sample_at;
      $end   = SuspectCase::where('pscr_sars_cov_2','positive')->orderByDesc('sample_at')->first()->sample_at;

      for ($i = $begin; $i <= $end; $i->modify('+1 day')) {
          $evolucion['Region'][$i->format("Y-m-d")] = 0;
          $evolucion['Alto Hospicio'][$i->format("Y-m-d")] = 0;
          $evolucion['Iquique'][$i->format("Y-m-d")] = 0;
          $evolucion['Pica'][$i->format("Y-m-d")] = 0;
          $evolucion['Pozo Almonte'][$i->format("Y-m-d")] = 0;
          $evolucion['Huara'][$i->format("Y-m-d")] = 0;
          $evolucion['Camiña'][$i->format("Y-m-d")] = 0;
      }

      foreach($patients as $patient) {
          $evolucion['Region'][$patient->suspectCases->where('pscr_sars_cov_2','positive')->first()->sample_at->format('Y-m-d')] += 1;
          if($patient->demographic) {
              $evolucion[$patient->demographic->commune][$patient->suspectCases->where('pscr_sars_cov_2','positive')->first()->sample_at->format('Y-m-d')] += 1;
          }
      }


      // foreach ($totales_dia as $dia) {
      //     list($fecha, $hora) = explode(' ', $dia->sample_at);
      //     $evolucion[$fecha] = $dia->total;
      // }

      // echo '<pre>';
      // print_r($evolucion);
      // die();


      foreach ($evolucion as $nombre_comuna => $comuna) {
          $acumulado = 0;
          foreach($comuna as  $dia => $valor) {
              $acumulado += $valor;
              $evo[$nombre_comuna][$dia] = $acumulado;
          }
      }
      $evolucion = $evo;

      $data = view('lab.suspect_cases.report', compact('patients', 'cases', 'cases_other_region', 'evolucion'))->render();

      $reportBackup = new ReportBackup2();
      $reportBackup->data = trim(preg_replace('/\r\n/', ' ', $data));
      $reportBackup->save();
    }
}
