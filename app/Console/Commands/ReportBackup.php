<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ReportBackup as ReportBackup2;
use App\SuspectCase;
use App\Patient;
use App\Ventilator;
use App\SanitaryResidence\Residence;
use App\SanitaryResidence\Booking;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Region;

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
        $bookings = Booking::where('status','Residencia Sanitaria')
                    ->whereHas('patient', function ($q) {
                        $q->where('status','Residencia Sanitaria');
                    })->get();
        $residences = Residence::all();

        //$comunas = env('COMUNAS');

        $patients = Patient::whereHas('suspectCases', function ($q) {
            $q->where('pscr_sars_cov_2','positive');
        })->with('suspectCases')->with('demographic')->get();

        $region_not = array_diff( [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16], [env('REGION')] );
        $patients = $patients->whereNotIn('demographic.region_id', $region_not);

        /* Calculo de gráfico de evolución */
        $begin = SuspectCase::where('pscr_sars_cov_2','positive')->orderBy('sample_at')->first()->sample_at;
        $end   = SuspectCase::where('pscr_sars_cov_2','positive')->orderByDesc('sample_at')->first()->sample_at;

        $communes = Region::find(env('REGION'))->communes;

        for ($i = $begin; $i <= $end; $i->modify('+1 day')) {
            $casos['Region'][$i->format("Y-m-d")] = 0;
            foreach($communes as $commune) {
                $casos[$commune->name][$i->format("Y-m-d")] = 0;
            }
        }

        foreach($patients as $patient) {
            $casos['Region'][$patient->suspectCases->where('pscr_sars_cov_2','positive')->first()->sample_at->format('Y-m-d')] += 1;
            if($patient->demographic and $patient->demographic->commune) {
                $casos[$patient->demographic->commune][$patient->suspectCases->where('pscr_sars_cov_2','positive')->first()->sample_at->format('Y-m-d')] += 1;
            }
        }

        foreach ($casos as $nombre_comuna => $comuna) {
            $acumulado = 0;
            foreach($comuna as  $dia => $valor) {
                $acumulado += $valor;
                $evolucion[$nombre_comuna][$dia] = $acumulado;
            }
        }
        /* Fin de calculo de evolución */


        /* Exámenes */
        $exams['total'] = SuspectCase::all()->count();
        $exams['positives'] = SuspectCase::where('pscr_sars_cov_2','positive')->get()->count();
        $exams['negatives'] = SuspectCase::where('pscr_sars_cov_2','negative')->get()->count();
        $exams['pending'] = SuspectCase::where('pscr_sars_cov_2','pending')->get()->count();
        $exams['undetermined'] = SuspectCase::where('pscr_sars_cov_2','undetermined')->get()->count();
        $exams['rejected'] = SuspectCase::where('pscr_sars_cov_2','rejected')->get()->count();

        /* Ventiladores */
        $ventilator = Ventilator::first();

        //echo '<pre>'; print_r($patients->where('status','Hospitalizado UCI (Ventilador)')->count()); die();
        //echo '<pre>'; print_r($evolucion); die();
        $data = view('lab.suspect_cases.reports.positives', compact('patients','evolucion','ventilator','residences','bookings','exams'))->render();;

        $reportBackup = new ReportBackup2();
        $reportBackup->data = $data;/// trim(preg_replace('/\r\n/', ' ', ));
        $reportBackup->save();
    }
}
