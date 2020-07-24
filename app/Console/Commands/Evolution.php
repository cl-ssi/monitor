<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Patient;
use App\SuspectCase;
use Illuminate\Support\Facades\Auth;

class Evolution extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:evolution';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        auth()->loginUsingId(1);
        //$communes = [5,6,7,8,9,10,11];
        //$communes = Auth::user()->communes();
        //$ids = Patient::whereHas('suspectCases', function ($q){$q->where('pcr_sars_cov_2', 'positive'); })->whereHas('demographic', function ($q) use($communes) {$q->whereIn('commune_id', $communes);})->pluck('id');


        $begin = SuspectCase::where('pcr_sars_cov_2','positive')
                ->orderBy('sample_at')
                ->first()->sample_at->startOfDay();
        $end   = SuspectCase::where('pcr_sars_cov_2','positive')
                ->orderByDesc('sample_at')
                ->first()->sample_at->endOfDay();

        $days = array();
        for ($i = $begin; $i <= $end; $i->modify('+1 day')) {
            $days[$i->format("Y-m-d")]=0;
        }

        $comunas = [5,6];

        $patients = Patient::has('firstPositive')
                    ->select('id')
                    ->whereHas('demographic', function ($q) use ($comunas){
                        $q->whereIn('commune_id', $comunas); // Hay que reemplazar las comunas
                    })
                    ->addSelect(['sample_at' => SuspectCase::selectRaw('DATE(sample_at) as sample_at')
                        ->whereColumn('patient_id','patients.id')
                        ->where('pcr_sars_cov_2','positive')
                        ->take(1)])
                    ->get();

        foreach($patients as $patient) {
            $days[$patient->sample_at] += 1;
        }

        $acumulado = 0;
        foreach($days as $day => $total) {
            $acumulado += $total;
            $evolucion[$day] = $acumulado;
        }

        print_r($evolucion);
        die();

        $evo = array();
        $acumulado = 0;
        for ($i = $begin; $i <= $end; $i->modify('+1 day')) {
            $acumulado += SuspectCase::distinct('patient_id')->whereIn('patient_id', $ids)->where('pcr_sars_cov_2','positive')->where('sample_at',$i->format("Y-m-d"))->count();
            $evo[$i->format("Y-m-d")] = $acumulado;
        }

        print_r($evo);
        return 0;
    }
}
