<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ReportBackup as ReportBackup2;
use App\SuspectCase;
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
      $cases_other_region = $cases_other_region->where('discharge_test', '<>', 1)->whereIn('patient.demographic.region',
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

      $totales_dia = DB::table('suspect_cases')
          ->select('sample_at', DB::raw('count(*) as total'))
          ->where('pscr_sars_cov_2', 'positive')
          ->groupBy('sample_at')
          ->orderBy('sample_at')
          ->get();


      $begin = new \DateTime($totales_dia->first()->sample_at);
      $end   = new \DateTime($totales_dia->last()->sample_at);

      for ($i = $begin; $i <= $end; $i->modify('+1 day')) {
          $evolucion[$i->format("Y-m-d")] = 0;
      }
      foreach ($totales_dia as $dia) {
          list($fecha, $hora) = explode(' ', $dia->sample_at);
          $evolucion[$fecha] = $dia->total;
      }

      $acumulado = 0;
      foreach ($evolucion as $key => $dia) {
          $acumulado += $dia;
          $evo[$key] = $acumulado;
      }
      $evolucion = $evo;
      $data = view('lab.suspect_cases.report', compact('cases', 'cases_other_region', 'evolucion'))->render();

      $reportBackup = new ReportBackup2();
      $reportBackup->data = json_encode($data);
      $reportBackup->save();
    }
}
