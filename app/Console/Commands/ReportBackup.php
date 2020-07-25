<?php

namespace App\Console\Commands;

use App\Commune;
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
        // set_time_limit(3600);

        /* Obtiene comunas .env */
        $communes_ids = array_map('trim', explode(",", env('COMUNAS')));
        $communes = Commune::whereIn('id', $communes_ids)->get();

        /* Valida que existan casos positivos */
        $patients = Patient::whereHas('suspectCases', function ($q) {
            $q->where('pcr_sars_cov_2', 'positive');
        })->whereHas('demographic', function ($q) use ($communes_ids) {
            $q->whereIn('commune_id', $communes_ids);
        });

        if ($patients->count() == 0){
            session()->flash('info', 'No existen casos positivos o no hay casos con dirección.');
            return redirect()->route('home');
        }

        /* Total casos */
        $casosTotalesArray = $this->getTotalPatients($communes_ids);

        /* Evolución */
        $evolucion = $this->getEvolucion($communes_ids);

        /* Exámenes */
        $exams['total'] = SuspectCase::count();
        $exams['positives'] = SuspectCase::where('pcr_sars_cov_2','positive')->count();
        $exams['negatives'] = SuspectCase::where('pcr_sars_cov_2','negative')->count();
        $exams['pending'] = SuspectCase::where('pcr_sars_cov_2','pending')->count();
        $exams['undetermined'] = SuspectCase::where('pcr_sars_cov_2','undetermined')->count();
        $exams['rejected'] = SuspectCase::where('pcr_sars_cov_2','rejected')->count();

        /* Ventiladores */
        list($ventilator, $UciPatients) = $this->getVentilatorStats($communes_ids);

        /* Fallecidos */
        $totalDeceasedArray = $this->getDeceasedPatients($communes_ids);

        /* Pacientes por rango edades */
        $ageRangeArray = $this->getRangeArray($communes_ids);

        /* Casos por comuna */
        $casesByCommuneArray = $this->getCasesByCommune($communes);
        $data = view('lab.suspect_cases.reports.positives', compact('evolucion','ventilator','exams','communes', 'ageRangeArray', 'casosTotalesArray', 'totalDeceasedArray', 'casesByCommuneArray', 'UciPatients') )->render();

        $reportBackup = new ReportBackup2();
        $reportBackup->data = $data;/// trim(preg_replace('/\r\n/', ' ', ));
        $reportBackup->save();
    }

    /**
     * @param array $communes_ids
     * @return array
     */
    public function getRangeArray(array $communes_ids): array
    {
        $ageRangeArray = array();
        for ($i = 10; $i <= 90; $i += 10) {

            $malePatients = Patient::whereHas('suspectCases', function ($q) {
                $q->where('pcr_sars_cov_2', 'positive');
            })->whereHas('demographic', function ($q) use ($communes_ids) {
                $q->whereIn('commune_id', $communes_ids);
            })->where('gender', 'male');

            $femalePatients = Patient::whereHas('suspectCases', function ($q) {
                $q->where('pcr_sars_cov_2', 'positive');
            })->whereHas('demographic', function ($q) use ($communes_ids) {
                $q->whereIn('commune_id', $communes_ids);
            })->where('gender', 'female');

            $subYearsBegin = $i . ' years';
            $subYearsEnd = $i - 10 . ' years';

            if ($i == 90) $subYearsBegin = $i + 60 . ' years';

            $begin = Carbon::now()->sub($subYearsBegin);
            $end = Carbon::now()->sub($subYearsEnd);

            $cantMale = $malePatients->whereBetween('birthday', [$begin, $end])->count();
            $cantFemale = $femalePatients->whereBetween('birthday', [$begin, $end])->count();

            array_push($ageRangeArray,
                array('male' => $cantMale,
                    'female' => $cantFemale));
        }
        $birthdayNullPatients = Patient::whereHas('suspectCases', function ($q) {
            $q->where('pcr_sars_cov_2', 'positive');
        })->whereHas('demographic', function ($q) use ($communes_ids) {
            $q->whereIn('commune_id', $communes_ids);
        })->whereNull('birthday')->count();

        array_push($ageRangeArray,
            array('null' => $birthdayNullPatients));

        return $ageRangeArray;
    }

    /**
     * @param array $communes_ids
     */
    public function getEvolucion($communes_ids)
    {
        $begin = SuspectCase::where('pcr_sars_cov_2', 'positive')
            ->orderBy('sample_at')
            ->first()->sample_at->startOfDay();
        $end = SuspectCase::where('pcr_sars_cov_2', 'positive')
            ->orderByDesc('sample_at')
            ->first()->sample_at->endOfDay();

        $days = array();
        for ($i = $begin; $i <= $end; $i->modify('+1 day')) {
            $days[$i->format("Y-m-d")] = 0;
        }

        $patients = Patient::has('firstPositive')
            ->select('id')
            ->whereHas('demographic', function ($q) use ($communes_ids) {
                $q->whereIn('commune_id', $communes_ids);
            })
            ->addSelect(['sample_at' => SuspectCase::selectRaw('DATE(sample_at) as sample_at')
                ->whereColumn('patient_id', 'patients.id')
                ->where('pcr_sars_cov_2', 'positive')
                ->take(1)])
            ->get();

        foreach ($patients as $patient) {
            $days[$patient->sample_at] += 1;
        }

        $acumulado = 0;
        foreach ($days as $day => $total) {
            $acumulado += $total;
            $evolucion[$day] = $acumulado;
        }
        return $evolucion;
    }

    /**
     * @param array $communes_ids
     * @return array
     */
    public function getTotalPatients(array $communes_ids): array
    {
        $patientsMale = Patient::whereHas('suspectCases', function ($q) {
            $q->where('pcr_sars_cov_2', 'positive');
        })->whereHas('demographic', function ($q) use ($communes_ids) {
            $q->whereIn('commune_id', $communes_ids);
        })->where('gender', 'male')->count();

        $patientsFemale = Patient::whereHas('suspectCases', function ($q) {
            $q->where('pcr_sars_cov_2', 'positive');
        })->whereHas('demographic', function ($q) use ($communes_ids) {
            $q->whereIn('commune_id', $communes_ids);
        })->where('gender', 'female')->count();

        $casosTotalesArray = array();
        $casosTotalesArray['male'] = $patientsMale;
        $casosTotalesArray['female'] = $patientsFemale;

        return $casosTotalesArray;
    }

    /**
     * @param array $communes_ids
     */
    public function getDeceasedPatients(array $communes_ids): array
    {
        $malePatients = Patient::whereHas('suspectCases', function ($q) {
            $q->where('pcr_sars_cov_2', 'positive');
        })->whereHas('demographic', function ($q) use ($communes_ids) {
            $q->whereIn('commune_id', $communes_ids);
        })->where('gender', 'male')
            ->where('status', 'Fallecido')->count();

        $femalePatients = Patient::whereHas('suspectCases', function ($q) {
            $q->where('pcr_sars_cov_2', 'positive');
        })->whereHas('demographic', function ($q) use ($communes_ids) {
            $q->whereIn('commune_id', $communes_ids);
        })->where('gender', 'female')
            ->where('status', 'Fallecido')->count();

        $totalDeceasedArray = array();
        $totalDeceasedArray['male'] = $malePatients;
        $totalDeceasedArray['female'] = $femalePatients;
        return $totalDeceasedArray;
    }

    /**
     * @param $communes
     * @return array
     */
    public function getCasesByCommune($communes): array
    {
        $casesByCommuneArray = array();
        foreach ($communes as $commune) {
            $cant = Patient::whereHas('suspectCases', function ($q) {
                $q->where('pcr_sars_cov_2', 'positive');
            })->whereHas('demographic', function ($q) use ($commune) {
                $q->where('commune_id', $commune->id);
            })->count();

            $casesByCommuneArray[$commune->id] = $cant;

        }

        $cant = Patient::whereHas('suspectCases', function ($q) {
            $q->where('pcr_sars_cov_2', 'positive');
        })->whereHas('demographic', function ($q) use ($commune) {
            $q->where('commune_id', null);
        })->count();
        $casesByCommuneArray['Sin Registro'] = $cant;
        return $casesByCommuneArray;
    }

    /**
     * @param array $communes_ids
     * @return array
     */
    public function getVentilatorStats(array $communes_ids): array
    {
        $ventilator = Ventilator::first();

        $UciPatients = Patient::whereHas('suspectCases', function ($q) {
            $q->where('pcr_sars_cov_2', 'positive');
        })->whereHas('demographic', function ($q) use ($communes_ids) {
            $q->whereIn('commune_id', $communes_ids);
        })->where('status', 'Hospitalizado UCI (Ventilador)')->count();
        return array($ventilator, $UciPatients);
    }
}
