<?php

namespace App\Http\Controllers;

use App\RapidTest;
use App\Establishment;
use http\Message;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use App\SuspectCase;
use App\Patient;
use App\Ventilator;
use App\File;
use App\EstablishmentUser;
use App\Tracing\Event;
use App\Tracing\EventType;
use App\SanitaryResidence\Residence;
use App\SanitaryResidence\Booking;
use Carbon\Carbon;
use App\Lab\Exam\SARSCoV2External;
use App\Laboratory;
use App\Region;
use App\WSMinsal;
use App\Commune;
use App\Country;
use App\Hl7ResultMessage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Hl7ErrorMessage;

use App\User;
use PDO;

class SuspectCaseReportController extends Controller
{
    public function positives()
    {
        // set_time_limit(3600);

        /* Obtiene comunas .env */
        $communes_ids = array_map('trim', explode(",", env('COMUNAS')));
        $communes = Commune::whereIn('id', $communes_ids)->get();

        /* Consulta base para las demás consultas de pacientes*/
        $patients = Patient::whereHas('suspectCases', function ($q) {
            $q->where('pcr_sars_cov_2', 'positive');
        })->whereHas('demographic', function ($q) use ($communes_ids) {
            $q->whereIn('commune_id', $communes_ids);
        });

        /* Valida que existan casos positivos */
        if ($patients->count() == 0) {
            session()->flash('info', 'No existen casos positivos o no hay casos con dirección.');
            return redirect()->route('home');
        }

        /* Total casos */
        $casosTotalesArray = $this->getTotalPatients($patients);

        /* Evolución */
        $evolucion = $this->getEvolucion($communes_ids);

        /* Exámenes */
        $exams['total'] = SuspectCase::count();
        $exams['positives'] = SuspectCase::where('pcr_sars_cov_2', 'positive')->count();
        $exams['negatives'] = SuspectCase::where('pcr_sars_cov_2', 'negative')->count();
        $exams['pending'] = SuspectCase::where('pcr_sars_cov_2', 'pending')->count();
        $exams['undetermined'] = SuspectCase::where('pcr_sars_cov_2', 'undetermined')->count();
        $exams['rejected'] = SuspectCase::where('pcr_sars_cov_2', 'rejected')->count();

        /* Ventiladores */
        list($ventilator, $UciPatients) = $this->getVentilatorStats($patients);

        /* Fallecidos */
        $totalDeceasedArray = $this->getDeceasedPatients($patients);

        /* Pacientes por rango edades */
        $ageRangeArray = $this->getRangeArray($patients);

        /* Casos por comuna */
        $casesByCommuneArray = $this->getCasesByCommune($communes);

        return view('lab.suspect_cases.reports.positives', compact('evolucion', 'ventilator', 'exams', 'communes', 'ageRangeArray', 'casosTotalesArray', 'totalDeceasedArray', 'casesByCommuneArray', 'UciPatients'));
    }

    /**
     * Obtiene positivos y acumulados por día.
     * @return Application|Factory|View
     */
    public function positivesOwn()
    {
        $communes_ids = Auth::user()->communes();
        $communes = Commune::find(Auth::user()->communes());

        /* Consulta base para las demás consultas de pacientes */
        $patients = Patient::whereHas('suspectCases', function ($q) {
            $q->where('pcr_sars_cov_2', 'positive');
        })->whereHas('demographic', function ($q) {
            $q->whereIn('commune_id', Auth::user()->communes());
        });

        $totalPatients = $this->getTotalPatientsOwn($patients);

        /* Evolución */
        $evolucion = $this->getEvolucionOwn($communes_ids);

        /* cálculo de positivos */
        $from = Carbon::now()->subDays(30);
        $to = Carbon::now();

        $suspectcases = SuspectCase::select('pcr_sars_cov_2_at')
            ->where('pcr_sars_cov_2', 'positive')
            ->whereBetween('pcr_sars_cov_2_at', [$from, $to])
            ->orderByDesc('sample_at')
            ->get();

        foreach ($suspectcases as $key => $suspectcase) {
            $positives[$suspectcase->pcr_sars_cov_2_at->format('d-m-Y')] = 0;
        }

        foreach ($suspectcases as $key => $suspectcase) {
            $positives[$suspectcase->pcr_sars_cov_2_at->format('d-m-Y')] += 1;
        }

        /* Fallecidos */
        $totalDeceasedArray = $this->getDeceasedPatients($patients);

        /*Se calcula número pacientes por rango edades */
        $ageRangeArray = $this->getRangeArray($patients);

        /* Casos por comuna */
        $casesByCommuneArray = $this->getCasesByCommuneByGender($communes);

        return view('lab.suspect_cases.reports.positives_own', compact('evolucion', 'communes', 'positives', 'ageRangeArray', 'totalPatients', 'totalDeceasedArray', 'casesByCommuneArray'));
    }

    /**
     * @return int
     */
    public function getTotalPatientsOwn($patients): int
    {
        $totalPatients = $patients->count();
        return $totalPatients;
    }

    /**
     * @param array $communes_ids
     * @param $patients
     * @return array
     */
    public function getRangeArray($patients): array
    {
        $ageRangeArray = array();
        for ($i = 10; $i <= 90; $i += 10) {

            $patients1 = clone $patients;
            $patients2 = clone $patients;

            $malePatients = $patients1->where('gender', 'male');
            $femalePatients = $patients2->where('gender', 'female');

            $subYearsBegin = $i . ' years';
            $subYearsEnd = $i - 10 . ' years';

            if ($i == 90) $subYearsBegin = $i + 60 . ' years';

            $begin = Carbon::now()->sub($subYearsBegin);
            $end = Carbon::now()->sub($subYearsEnd);

            $cantMale = $malePatients->whereBetween('birthday', [$begin, $end])->count();
            $cantFemale = $femalePatients->whereBetween('birthday', [$begin, $end])->count();

            array_push(
                $ageRangeArray,
                array(
                    'male' => $cantMale,
                    'female' => $cantFemale
                )
            );
        }

        $patients3 = clone $patients;
        $birthdayNullPatients = $patients3->whereNull('birthday')->count();

        array_push(
            $ageRangeArray,
            array('null' => $birthdayNullPatients)
        );

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
     */
    public function getEvolucionOwn($communes_ids)
    {
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

        $begin = SuspectCase::where('pcr_sars_cov_2', 'positive')
            ->whereIn('patient_id', $patients->pluck('id')->toArray())
            ->orderBy('sample_at')
            ->first()->sample_at->startOfDay();
        $end = SuspectCase::where('pcr_sars_cov_2', 'positive')
            ->whereIn('patient_id', $patients->pluck('id')->toArray())
            ->orderByDesc('sample_at')
            ->first()->sample_at->endOfDay();

        $days = array();
        for ($i = $begin; $i <= $end; $i->modify('+1 day')) {
            $days[$i->format("Y-m-d")] = 0;
        }

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
     * @param $patientBase
     * @return array
     */
    public function getTotalPatients($patients): array
    {
        $patients1 = clone $patients;
        $patients2 = clone $patients;
        $patientsMale = $patients1->where('gender', 'male')->count();
        $patientsFemale = $patients2->where('gender', 'female')->count();

        $casosTotalesArray = array();
        $casosTotalesArray['male'] = $patientsMale;
        $casosTotalesArray['female'] = $patientsFemale;

        return $casosTotalesArray;
    }

    /**
     * @param array $communes_ids
     * @param $patients
     * @return array
     */
    public function getDeceasedPatients($patients): array
    {
        $patients1 = clone $patients;
        $patients2 = clone $patients;

        $malePatients = $patients1->where('gender', 'male')
            ->where('status', 'Fallecido')->count();

        $femalePatients = $patients2->where('gender', 'female')
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
     * @param $communes
     * @return array
     */
    public function getCasesByCommuneByGender($communes): array
    {
        $casesByCommuneArray = array();
        foreach ($communes as $commune) {
            $cantMale = Patient::whereHas('suspectCases', function ($q) {
                $q->where('pcr_sars_cov_2', 'positive');
            })->whereHas('demographic', function ($q) use ($commune) {
                $q->where('commune_id', $commune->id);
            })->where('gender', 'male')
                ->count();

            $cantFemale = Patient::whereHas('suspectCases', function ($q) {
                $q->where('pcr_sars_cov_2', 'positive');
            })->whereHas('demographic', function ($q) use ($commune) {
                $q->where('commune_id', $commune->id);
            })->where('gender', 'female')
                ->count();

            $casesByCommuneArray[$commune->id]['male'] = $cantMale;
            $casesByCommuneArray[$commune->id]['female'] = $cantFemale;
        }

        return $casesByCommuneArray;
    }

    /**
     * @param array $communes_ids
     * @param $param
     * @return array
     */
    public function getVentilatorStats($patients): array
    {
        $patients1 = clone $patients;
        $ventilator = Ventilator::first();

        $UciPatients = $patients1->where('status', 'Hospitalizado UCI (Ventilador)')->count();
        return array($ventilator, $UciPatients);
    }

    /*****************************************************/
    /*                 SEGUIMIENTO CASOS                 */
    /*****************************************************/
    public function case_tracing(Request $request)
    {

        $env_communes = array_map('trim', explode(",", env('COMUNAS')));

        $patients = Patient::whereHas('suspectCases', function ($q) {
            $q->where('pcr_sars_cov_2', 'positive');
        })->whereHas('demographic', function ($q) use ($env_communes) {
            $q->whereIn('commune_id', $env_communes);
        })
            ->with('inmunoTests')
            ->latest()
            ->paginate(500);

        $patientsNoDemographic = Patient::whereHas('suspectCases', function ($q) {
            $q->where('pcr_sars_cov_2', 'positive');
        })->doesntHave('demographic')
            ->with('inmunoTests')
            ->get();

        $max_cases = 0;
        $max_cases_inmuno = 0;
        foreach ($patients as $patient) {
            if ($max_cases < $patient->suspectCases->count()) {
                $max_cases = $patient->suspectCases->count();
            }
            if ($max_cases_inmuno < $patient->inmunoTests->count()) {
                $max_cases_inmuno = $patient->inmunoTests->count();
            }
        }

        $max_cases_no_demographic = 0;
        $max_cases_inmuno_no_demographic = 0;
        foreach ($patientsNoDemographic as $patient) {
            if ($max_cases_no_demographic < $patient->suspectCases->count()) {
                $max_cases_no_demographic = $patient->suspectCases->count();
            }
            if ($max_cases_inmuno_no_demographic < $patient->inmunoTests->count()) {
                $max_cases_inmuno_no_demographic = $patient->inmunoTests->count();
            }
        }

        return view('lab.suspect_cases.reports.case_tracing', compact('patients', 'max_cases', 'max_cases_inmuno', 'patientsNoDemographic', 'max_cases_no_demographic', 'max_cases_inmuno_no_demographic'));
    }


    public function ownIndexFilter(request $request, Laboratory $laboratory)
    {

        $laboratories = Laboratory::All();
        //$laboratory = Laboratory::All();
        if ($from = $request->has('from')) {
            $from = $request->get('from');
            $to = $request->get('to');
        } else {
            $from = date("Y-m-d 21:00", time() - 60 * 60 * 24);
            $to = date("Y-m-d 20:59");
        }

        if($request->get('laboratory_id')==0)
        {
            $cases = SuspectCase::whereBetween('sample_at', [$from, $to])->where(function ($q) {
            $q->whereIn('establishment_id', Auth::user()->establishments->pluck('id'))
                ->orWhere('user_id', Auth::user()->id);
        })->orderBy('suspect_cases.id', 'desc')->get();
        }
        else
        {
            $cases = SuspectCase::where('laboratory_id', $request->get('laboratory_id'))->whereBetween('sample_at', [$from, $to])->where(function ($q) {
                $q->whereIn('establishment_id', Auth::user()->establishments->pluck('id'))
                    ->orWhere('user_id', Auth::user()->id);
            })->orderBy('suspect_cases.id', 'desc')->get();
        }

        return view('lab.suspect_cases.reports.ownindexfilter', compact('request', 'cases', 'from', 'to','laboratories'));
    }




    public function tracing_minsal(Request $request)
    {

        if ($request->has('date_from') && $request->has('date_to')) {
            $date_from = $request->get('date_from');
            $date_to = $request->get('date_to');
        } else {
            $date_from = Carbon::now();
            $date_to = Carbon::now();
        }

        $patients = Patient::whereHas('tracing', function ($q) use ($date_to, $date_from) {
                $q->where('index', '1')
                    ->whereIn('establishment_id', auth()->user()->establishments->pluck('id'))
                    ->whereBetween('notification_at', [new Carbon($date_from), new Carbon($date_to)]);
            })
            ->with('contactPatient')
            ->with('tracing')
            ->with('suspectCases')
            ->get();

        //        dd($patients);

        return view('lab.suspect_cases.reports.tracing_minsal', compact('patients', 'request'));
    }

    public function tracing_minsal_by_patient(Request $request)
    {
        if ($request->has('run')) {
            $patients = Patient::whereHas('tracing', function ($q) {
                $q->where('index', '1')
                    ->whereIn('establishment_id', auth()->user()->establishments->pluck('id'));
            })
                ->where('run', $request->get('run'))
                ->orWhere('other_identification', $request->get('run'))
                ->with('contactPatient')
                ->with('tracing')
                ->with('suspectCases')
                ->get();
        } else {
            $patients = collect(new Patient);
        }

        return view('lab.suspect_cases.reports.tracing_minsal_by_patient', compact('patients', 'request'));
    }

    public function tracingByCommunes(Request $request)
    {


        if ($request->has('date')) {
            $date = $request->get('date');
        } else {
            $date = Carbon::now();
        }

        // ----------------------- crear arreglo ------------------------------
        $communes = Commune::where('region_id', env('REGION'))->orderBy('name')->get();
        foreach ($communes as $key => $commune) {
            $report[$commune->id]['Comuna'] = $commune->name;
            $report[$commune->id]['positives'] = 0;
            $report[$commune->id]['car'] = 0;
            $report[$commune->id]['curso'] = 0;
            $report[$commune->id]['terminado'] = 0;
        }

        $from = $request->get('date') . ' 00:00:00';
        $to = $request->get('date') . ' 23:59:59';

        $patients = Patient::whereHas('suspectCases', function ($q) use ($date) {
            $q->where('pcr_sars_cov_2', 'positive')
                ->whereDate('pcr_sars_cov_2_at', $date);
        })
            ->whereHas('demographic', function ($q) {
                $q->where('region_id', env('REGION'));
            })
            ->get();

        foreach ($patients as $patient) {

            $report[$patient->demographic->commune_id]['positives'] += 1;

            foreach ($patient->contactPatient as $contact) {
                if ($contact->patient_id == $patient->id) {
                    // dd($contact);
                    $report[$patient->demographic->commune_id]['car'] += 1;
                }
            }

            if ($patient->tracing) {
                if ($patient->tracing->status == 1) {
                    $report[$patient->demographic->commune_id]['curso'] += 1;
                }
                if ($patient->tracing->status == null or $patient->tracing->status == 0) {
                    $report[$patient->demographic->commune_id]['terminado'] += 1;
                }
            }
        }

        //dd($report);



        // if ($patients->count() == 0){
        //     session()->flash('info', 'No existen casos positivos o no hay casos con dirección.');
        //     //return redirect()->route('home');
        // }



        $communes_ids = array_map('trim', explode(",", env('COMUNAS')));
        $communes = Commune::whereIn('id', $communes_ids)->get();



        return view('lab.suspect_cases.reports.tracingbycommune', compact('request', 'report', 'communes', 'patients'));
    }

    public function case_tracing_export()
    {
        $env_communes = array_map('trim', explode(",", env('COMUNAS')));

        $patients = Patient::whereHas('suspectCases', function ($q) {
            $q->where('pcr_sars_cov_2', 'positive');
        })->whereHas('demographic', function ($q) use ($env_communes) {
            $q->whereIn('commune_id', $env_communes);
        })
            ->with('inmunoTests')
            ->get();

        $max_cases = 0;
        $max_cases_inmuno = 0;
        foreach ($patients as $patient) {
            if ($max_cases < $patient->suspectCases->count()) {
                $max_cases = $patient->suspectCases->count();
            }
            if ($max_cases_inmuno < $patient->inmunoTests->count()) {
                $max_cases_inmuno = $patient->inmunoTests->count();
            }
        }

        /* CONTRUCCION DEL HEADER DEL ARCHIVO */
        $columnas_paciente = array(
            'Paciente',
            'Identificador',
            'Edad',
            'Sexo',
            'Comuna',
            'Nacionalidad',
            'Telefonos',
            'Estado',
        );

        $columnas_covid = array();
        for ($i = 1; $i <= $max_cases; $i++) {
            $columnas_covid[] = 'PCR ' . $i;
            $columnas_covid[] = 'Fecha Muestra ' . $i;
            $columnas_covid[] = 'Fecha Resultado ' . $i;
            $columnas_covid[] = 'Resultado ' . $i;
            $columnas_covid[] = 'S ' . $i;
        }

        $columnas_inmuno = array();
        for ($i = 1; $i <= $max_cases_inmuno; $i++) {
            $columnas_inmuno[] = 'IgG/IgM ' . $i;
            $columnas_inmuno[] = 'Fecha Test' . $i;
            $columnas_inmuno[] = 'IgG ' . $i;
            $columnas_inmuno[] = 'IgM ' . $i;
            $columnas_inmuno[] = 'Control ' . $i;
        }

        $columnas_cases = array(
            'Fecha IFD',
            'IFD',
            'Origen',
            'S.Epidemiológica',
            'Epivigila',
            'PAHO FLU',
            'Gestante',
            'Contacto directo',
            'Fecha envío',
            'Laboratorio',
            'Fecha Entrega Resultado',
            'Mecanismo',
            'Fecha Alta',
            'Observación',
        );

        $columnas = array_merge($columnas_paciente, $columnas_covid, $columnas_inmuno, $columnas_cases);

        foreach ($patients as $key => $patient) {
            $casos[$key][] = $patient->fullName;
            $casos[$key][] = $patient->identifier;
            $casos[$key][] = $patient->age;
            $casos[$key][] = $patient->genderEsp;
            $casos[$key][] = ($patient->demographic and $patient->demographic->commune) ? $patient->demographic->commune->name : '';
            $casos[$key][] = ($patient->demographic) ? $patient->demographic->nationality : '';
            $casos[$key][] = ($patient->demographic) ? $patient->demographic->fullTelephones : '';
            $casos[$key][] = $patient->status;
            foreach ($patient->suspectCases as $suspectCase) {
                $casos[$key][] = $suspectCase->id;
                $casos[$key][] = $suspectCase->sample_at->format('Y-m-d');
                $casos[$key][] = ($suspectCase->pcr_sars_cov_2_at) ? $suspectCase->pcr_sars_cov_2_at->format('Y-m-d') : '';
                $casos[$key][] = $suspectCase->covid19;
                $casos[$key][] = $suspectCase->symptoms;
            }
            for ($i = $patient->suspectCases->count(); $i < $max_cases; $i++) {
                $casos[$key][] = '';
                $casos[$key][] = '';
                $casos[$key][] = '';
                $casos[$key][] = '';
                $casos[$key][] = '';
            }
            foreach ($patient->inmunoTests as $inmunoTest) {
                $casos[$key][] = $inmunoTest->id;
                $casos[$key][] = ($inmunoTest->register_at) ? $inmunoTest->register_at->format('Y-m-d H:i:s') : '';
                $casos[$key][] = strtoupper(($inmunoTest->IgValue) ? $inmunoTest->IgValue : '');
                $casos[$key][] = strtoupper(($inmunoTest->ImValue) ? $inmunoTest->ImValue : '');
                $casos[$key][] = strtoupper($inmunoTest->ControlValue);
            }
            for ($i = $patient->inmunoTests->count(); $i < $max_cases_inmuno; $i++) {
                $casos[$key][] = '';
                $casos[$key][] = '';
                $casos[$key][] = '';
                $casos[$key][] = '';
                $casos[$key][] = '';
            }
            $casos[$key][] = ($patient->suspectCases->first()->result_ifd_at) ? $patient->suspectCases->first()->result_ifd_at->format('Y-m-d') : '';
            $casos[$key][] = $patient->suspectCases->first()->result_ifd;
            $casos[$key][] = ($patient->suspectCases->first()->establishment) ? $patient->suspectCases->first()->establishment->alias : '';
            $casos[$key][] = $patient->suspectCases->first()->epidemiological_week;
            $casos[$key][] = $patient->suspectCases->first()->epivigila;
            $casos[$key][] = $patient->suspectCases->first()->paho_flu;
            $casos[$key][] = ($patient->suspectCases->first()->gestation == 1) ? 'Sí' : '';
            $casos[$key][] = ($patient->suspectCases->first()->close_contact == 1) ? 'Sí' : '';
            $casos[$key][] = ($patient->suspectCases->first()->sent_external_lab_at) ? $patient->suspectCases->first()->sent_external_lab_at->format('Y-m-d') : '';
            $casos[$key][] = $patient->suspectCases->first()->procesingLab;
            $casos[$key][] = ($patient->suspectCases->first()->notification_at) ? $patient->suspectCases->first()->notification_at->format('Y-m-d') : '';
            $casos[$key][] = $patient->suspectCases->first()->notification_mechanism;
            $casos[$key][] = ($patient->suspectCases->first()->discharged_at) ? $patient->suspectCases->first()->discharged_at->format('Y-m-d') : '';
            $casos[$key][] = $patient->suspectCases->first()->observation;
        }

        $callback = function () use ($casos, $columnas) {

            $file = fopen('php://output', 'w');
            fputs($file, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));

            fputcsv($file, $columnas, ';');

            foreach ($casos as $fila) {
                fputcsv($file, $fila, ';');
            }
            fclose($file);
        };

        $headers = array(
            "Content-type" => "text",
            "Content-Disposition" => "attachment; filename=seguimiento.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        return response()->stream($callback, 200, $headers);
    }



    /*****************************************************/
    /*                  REPORTE MINSAL                   */
    /*****************************************************/
    public function report_minsal(Request $request, Laboratory $laboratory)
    {

        if ($from = $request->has('from')) {
            $from = $request->get('from');
            $to = $request->get('to');
        } else {
            $from = date("Y-m-d 21:00", time() - 60 * 60 * 24);
            $to = date("Y-m-d 20:59");
        }

        $externos = SARSCoV2External::whereBetween('result_at', [$from, $to])->get();

        $cases = SuspectCase::where('laboratory_id', $laboratory->id)
            ->whereBetween('pcr_sars_cov_2_at', [$from, $to])
            ->whereNull('external_laboratory')
            ->get()
            ->sortByDesc('pcr_sars_cov_2_at');
        return view('lab.suspect_cases.reports.minsal', compact('cases', 'laboratory', 'externos', 'from', 'to', 'request'));
    }

    /*****************************************************/
    /*                  REPORTE RECEPCIONADOS            */
    /*****************************************************/
    public function reception_report(Request $request, Laboratory $laboratory)
    {

        if ($from = $request->has('from')) {
            $from = $request->get('from');
            $to = $request->get('to');
        } else {
            $from = date("Y-m-d 21:00", time() - 60 * 60 * 24);
            $to = date("Y-m-d 20:59");
        }

        $externos = SARSCoV2External::whereBetween('result_at', [$from, $to])->get();

        $cases = SuspectCase::where('laboratory_id', $laboratory->id)
            ->whereBetween('reception_at', [$from, $to])
            ->whereNull('external_laboratory')
            ->get()
            ->sortByDesc('reception_at');
        return view('lab.suspect_cases.reports.reception_report', compact('cases', 'laboratory', 'externos', 'from', 'to', 'request'));
    }


    /*****************************************************/
    /*                  REPORTE MINSAL WS                */
    /*****************************************************/
    public function report_minsal_ws(Request $request)
    {
        $from = '2021-09-20 00:00'; //date("Y-m-d 21:00:00", time() - 60 * 60 * 24); cambiar también en from de ws_minsal()
        $to = date("Y-m-d 20:59:59");

        $laboratory_id = 1;
        if ($request->all()) {
            $laboratory_id = $request->laboratory_id;
        } else {
            $request->laboratory_id = 1;
        }

        $cases = SuspectCase::where('laboratory_id', $laboratory_id)
            ->whereBetween('pcr_sars_cov_2_at', [$from, $to])
            ->whereNull('external_laboratory')
            ->whereNULL('minsal_ws_id')
            // ->where('id',20370)
            ->get()
            ->sortByDesc('pcr_sars_cov_2_at');
        // dd($cases);

        $laboratories = Laboratory::where('minsal_ws', 1)->get();
        // $laboratories = Laboratory::where('id',1)->get();

        return view('lab.suspect_cases.reports.minsal_ws', compact('cases', 'request', 'laboratories')); //,'externos'));
    }


    /*****************************************************/
    /*                    WS - Minsal                    */
    /*****************************************************/
    public function ws_minsal(Request $request)
    {

        // dd($request);
        $from = '2021-09-20 00:00'; //date("Y-m-d 21:00:00", time() - 60 * 60 * 24);
        $to = date("Y-m-d 20:59:59");

        $laboratory_id = 1;
        if ($request->all()) {
            $laboratory_id = $request->laboratory_id;
        } else {
            $request->laboratory_id = 1;
        }

        $laboratories = Laboratory::where('minsal_ws', $laboratory_id)->get();
        // $laboratories = Laboratory::where('id',1)->get();

        $cases = SuspectCase::where('laboratory_id', $request->laboratory_id)
            ->whereBetween('pcr_sars_cov_2_at', [$from, $to])
            ->whereNull('external_laboratory')
            ->whereNULL('minsal_ws_id')
            // ->where('id',20370)
            ->get()
            ->sortByDesc('pcr_sars_cov_2_at');
        // dd($cases);

        foreach ($cases as $key => $case) {
            // if ($case->run_medic != 0) {
            if ($case->patient->demographic) {
                // dd("");
                $response = WSMinsal::crea_muestra_v2($case);
                if ($response['status'] == 0) {
                    session()->flash('info', 'Error al subir muestra ' . $case->id . ' a MINSAL. ' . $response['msg']);
                    return redirect()->back();
                    // return view('lab.suspect_cases.reports.minsal_ws', compact('cases', 'request','laboratories'));
                } else {
                    $response = WSMinsal::recepciona_muestra($case);
                    if ($response['status'] == 0) {
                        session()->flash('info', 'Error al recepcionar muestra ' . $case->id . ' en MINSAL. ' . $response['msg']);
                        return redirect()->back();
                        // return view('lab.suspect_cases.reports.minsal_ws', compact('cases', 'request','laboratories'));
                    } else {
                        $response = WSMinsal::resultado_muestra($case);
                        if ($response['status'] == 0) {
                            $case->ws_pntm_mass_sending = false;
                            $case->save();
                            session()->flash('info', 'Error al subir resultado de muestra ' . $case->id . ' en MINSAL. ' . $response['msg']);
                            return redirect()->back();
                            // return view('lab.suspect_cases.reports.minsal_ws', compact('cases', 'request','laboratories'));
                        }
                        if ($response['status'] == 1) {
                            $case->ws_pntm_mass_sending = true;
                            $case->save();
                        }

                    }
                }
            } else {
                session()->flash('info', 'Error al subir la muestra ' . $case->id . ' en MINSAL. No existen datos demográficos asociados.');
                return redirect()->back();
            }
            // }else{
            //     session()->flash('info', 'No se detectó run de médico registrado en muestra:  ' . $case->id);
            //     return redirect()->back();
            // }
        }

        session()->flash('success', 'Se ha subido la información a sistema MINSAL.');
        // return redirect()->back();
        return view('lab.suspect_cases.reports.minsal_ws', compact('cases', 'request', 'laboratories'));
    }

    public function ws_minsal_pendings_creation(Request $request)
    {
        set_time_limit(3600);

        $from = '2022-01-20 14:15:00'; //date("Y-m-d 21:00:00", time() - 60 * 60 * 24);
        $errors = '';

        // $case = SuspectCase::find('114122');
        // dump($case);

        $casosCreados = SuspectCase::whereNull('minsal_ws_id')
            ->whereNull('external_laboratory')
            //->whereNull('reception_at')
            ->where('created_at', '>=', $from)
            ->whereHas('laboratory', function ($q){
              $q->where('minsal_ws', 1);
            })
            ->get();

        // dd($casosCreados);

       foreach ($casosCreados as $case){
            $response = WSMinsal::crea_muestra_v2($case);
            if ($response['status'] == 0) {
                $errors = $errors . "case: " .$case->id . " " . $response['msg'] . "<br>";
            }
       }

        if($errors){
            session()->flash('info', $errors);
        }else {
            session()->flash('success', 'Se han sincronizado muestras recepcion con PNTM.');
        }

        return redirect()->back();
    }


    /**
     * Funcion para sincronizar receptions muestras que quedaron a mitad de proceso durante baja de sistema
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function ws_minsal_pendings_reception(Request $request)
    {
        set_time_limit(3600);

        $from = '2022-01-20 14:15:00'; //date("Y-m-d 21:00:00", time() - 60 * 60 * 24);
        $errors = '';

        $casosRecepcionados = SuspectCase::whereNotNull('minsal_ws_id')
            ->whereNull('external_laboratory')
            ->where('reception_at', '>=', $from)->get();

//        $casosRecepcionados = SuspectCase::whereNotNull('minsal_ws_id')
//            ->whereNotNull('reception_at')
//            ->whereNotNull('derivation_internal_lab_at')
//            ->get();

//        dd($casosRecepcionados);

        foreach ($casosRecepcionados as $case){
            $response = WSMinsal::recepciona_muestra($case);
            if ($response['status'] == 0) {
                $errors = $errors . "case: " .$case->id . " " . $response['msg'] . "<br>";
            }
        }

//        dump('casos recepcionados', $casosRecepcionados->pluck('id'));
//        dd('casos con resultado',$casosConResultado->pluck('id'));

        if($errors){
            session()->flash('info', $errors);
        }else {
            session()->flash('success', 'Se han sincronizado muestras recepcion con PNTM.');
        }

        return redirect()->back();
    }

    /**
     * Funcion para sincronizar result muestras que quedaron a mitad de proceso durante baja de sistema
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function ws_minsal_pendings_result(Request $request)
    {
        set_time_limit(3600);

        $from = '2022-01-20 14:15:00';
        $to = '2022-01-21 09:30:00';
        $errors = '';

        $casosConResultado = SuspectCase::whereNotNull('minsal_ws_id')
            ->whereNull('external_laboratory')
            ->where('pcr_result_added_at', '>=', $from)
            ->where('pcr_result_added_at', '<=', $to)->get();

//        $casosConResultado = SuspectCase::whereNotNull('minsal_ws_id')
//            ->whereNotNull('pcr_sars_cov_2_at')
//            ->whereNotNull('derivation_internal_lab_at')
//            ->get();

//        dd($casosConResultado);

        foreach ($casosConResultado as $case) {
            $response = WSMinsal::resultado_muestra($case);
            if ($response['status'] == 0) {
                $errors = $errors . "case: " .$case->id . " " . $response['msg'] . "<br>";
            }
        }

//        dump('casos recepcionados', $casosRecepcionados->pluck('id'));
//        dd('casos con resultado',$casosConResultado->pluck('id'));

        if($errors){
            session()->flash('info', $errors);
        }else {
            session()->flash('success', 'Se han sincronizado muestras resultado con PNTM.');
        }

        return redirect()->back();
    }


    /*****************************************************/
    /*                  REPORTE SEREMI                   */
    /*****************************************************/
    public function report_seremi(Laboratory $laboratory)
    {
        $from = Carbon::now()->subDays(15);
        $to = Carbon::now();

        $cases = SuspectCase::where('laboratory_id', $laboratory->id)->
            whereBetween('created_at', [$from, $to])->
            get()->
            sortDesc();
        return view('lab.suspect_cases.reports.seremi', compact('cases', 'laboratory'));
    }



    /*****************************************************/
    /*                REPORTE GESTANTES                  */
    /*****************************************************/
    public function gestants()
    {
        $patients = Patient::whereHas('suspectCases', function ($q) {
            $q->where('gestation', 1);
        })->with('suspectCases')->get();

        return view('lab.suspect_cases.reports.gestants', compact('patients'));
    }

    /*****************************************************/
    /*              CONTADOR DE POSITIVOS                */
    /*****************************************************/
    public function countPositives(Request $request)
    {
        $patients = Patient::positivesList();

        if ($request->input('residence')) {
            $bookings = Booking::where('status', 'Residencia Sanitaria')
                ->whereHas('patient', function ($q) {
                    $q->where('status', 'Residencia Sanitaria');
                })->get();
            $booking_ct = $bookings->where('room.residence_id', $request->input('residence'))->count();
            return $patients->count() . "\n" . $booking_ct;
        }

        return $patients->count();
    }



    public function case_chart(Request $request)
    {
        // $from = $request->has('from'). ' 00:00:00';
        // $to   = $request->has('to'). ' 23:59:59';
        if ($from = $request->has('from')) {
            $from = $request->get('from') . ' 00:00:00';
            $to = $request->get('to') . ' 23:59:59';
        } else {
            $from = Carbon::now()->firstOfMonth();
            $to = Carbon::now()->lastOfMonth();
        }

        $suspectCases = SuspectCase::whereBetween('sample_at', [$from, $to])->get();
        // ::latest('id')->get();
        $data = array();
        foreach ($suspectCases as $key => $suspectCase) {
            if ($suspectCase->pcr_sars_cov_2 == 'positive' || $suspectCase->pcr_sars_cov_2 == 'pending') {
                $data[date("d", strtotime($suspectCase->sample_at)) . "/" . date("m", strtotime($suspectCase->sample_at)) . "/" . date("Y", strtotime($suspectCase->sample_at))]['day'] = date("d", strtotime($suspectCase->sample_at));
                $data[date("d", strtotime($suspectCase->sample_at)) . "/" . date("m", strtotime($suspectCase->sample_at)) . "/" . date("Y", strtotime($suspectCase->sample_at))]['month'] = date("m", strtotime($suspectCase->sample_at)) - 1;
                $data[date("d", strtotime($suspectCase->sample_at)) . "/" . date("m", strtotime($suspectCase->sample_at)) . "/" . date("Y", strtotime($suspectCase->sample_at))]['year'] = date("Y", strtotime($suspectCase->sample_at));
                $data[date("d", strtotime($suspectCase->sample_at)) . "/" . date("m", strtotime($suspectCase->sample_at)) . "/" . date("Y", strtotime($suspectCase->sample_at))]['pendientes'] = 0;
                $data[date("d", strtotime($suspectCase->sample_at)) . "/" . date("m", strtotime($suspectCase->sample_at)) . "/" . date("Y", strtotime($suspectCase->sample_at))]['positivos'] = 0;
            }
            // $suspectCase->day = date("d", strtotime($suspectCase->sample_at));
            // $suspectCase->month = date("m", strtotime($suspectCase->sample_at))-1;
            // $suspectCase->year = date("Y", strtotime($suspectCase->sample_at));


        }

        foreach ($suspectCases as $key => $suspectCase) {
            if ($suspectCase->pcr_sars_cov_2 == 'pending') {
                $data[date("d", strtotime($suspectCase->sample_at)) . "/" . date("m", strtotime($suspectCase->sample_at)) . "/" . date("Y", strtotime($suspectCase->sample_at))]['pendientes'] += 1;
            }
            if ($suspectCase->pcr_sars_cov_2 == 'positive') {
                $data[date("d", strtotime($suspectCase->sample_at)) . "/" . date("m", strtotime($suspectCase->sample_at)) . "/" . date("Y", strtotime($suspectCase->sample_at))]['positivos'] += 1;
            }
        }

        return view('lab.suspect_cases.reports.case_chart', compact('suspectCases', 'data', 'from', 'to'));
    }



    public function exams_with_result(Request $request)
    {
        $from = Carbon::now()->subDays(2);
        $to = Carbon::now();
        //        $files = File::whereBetween('created_at', [$from, $to])
        //                   ->whereHas('suspectCase', function ($query) {
        //                        $query->where('pcr_sars_cov_2', 'like', 'positive');
        //                    })
        //                   ->orderBy('created_at','DESC')->get();

        $suspectCases = SuspectCase::whereBetween('pcr_sars_cov_2_at', [$from, $to])
            ->where('pcr_sars_cov_2', 'like', 'positive')
            ->where('file', true)
            ->orderBy('created_at', 'DESC')->get();


        $suspectCasesUnap = SuspectCase::whereBetween('created_at', [$from, $to])
            ->where('pcr_sars_cov_2', 'like', 'positive')
            ->where('laboratory_id', 2)
            ->get();

        return view('lab.suspect_cases.reports.exams_with_result', compact('suspectCases', 'suspectCasesUnap'));
    }

    /**
     * Obtiene suspectsCases positivos con datos de demographics por
     * rango de fecha
     * @param Request $request
     * @return Application|Factory|View
     */
    public function positivesByDateRange(Request $request)
    {

        if ($from = $request->has('from')) {
            $from = $request->get('from') . ' 00:00:00';
            $to = $request->get('to') . ' 23:59:59';
        } else {
            $from = Carbon::yesterday();
            $to = Carbon::now();
        }

        $communes_ids = array_map('trim', explode(",", env('COMUNAS')));
        $communes = Commune::whereIn('id', $communes_ids)->get();

        $selectedCommune = $request->get('commune');

        $suspectCases = SuspectCase::whereBetween('pcr_sars_cov_2_at', [$from, $to])
            ->where('pcr_sars_cov_2', 'positive')
            ->when($selectedCommune, function ($q) use ($selectedCommune) {
                return $q->whereHas('patient', function ($q) use ($selectedCommune) {
                    $q->whereHas('demographic', function ($q) use ($selectedCommune) {
                        $q->where('commune_id', $selectedCommune);
                    });
                });
            })
            ->orderBy('pcr_sars_cov_2_at')
            ->get();

        return view('lab.suspect_cases.reports.positivesByDateRange', compact('suspectCases', 'from', 'to', 'communes', 'selectedCommune'));
    }

    /*****************************************************/
    /*            REPORTE HOSPITALIZADOS                 */
    /*****************************************************/
    public function hospitalized()
    {
        $patients = Patient::whereHas('suspectCases', function ($q) {
            $q->where('pcr_sars_cov_2', 'positive');
        })->whereIn('status', [
            'Hospitalizado Básico',
            'Hospitalizado Medio',
            'Hospitalizado UTI',
            'Hospitalizado UCI',
            'Hospitalizado UCI (Ventilador)'
        ])
            ->orderBy('status')
            ->get();

        return view('lab.suspect_cases.reports.hospitalized', compact('patients'));
    }

    /*****************************************************/
    /*     REPORTE HOSPITALIZADOS POR COMUNAS USUARIO    */
    /*****************************************************/
    public function hospitalizedByUserCommunes()
    {

        $patients = Patient::whereHas('suspectCases', function ($q) {
            $q->where('pcr_sars_cov_2', 'positive');
        })->whereIn('status', [
            'Hospitalizado Básico',
            'Hospitalizado Medio',
            'Hospitalizado UTI',
            'Hospitalizado UCI',
            'Hospitalizado UCI (Ventilador)'
        ])->whereHas('demographic', function ($q) {
            $q->whereIn('commune_id', auth()->user()->communes());
        })
            ->orderBy('status')
            ->get();

        $byUserCommune = true;

        return view('lab.suspect_cases.reports.hospitalized', compact('patients', 'byUserCommune'));
    }

    /*****************************************************/
    /*            REPORTE FALLECIDOS                     */
    /*****************************************************/
    public function deceased()
    {
        $patients = Patient::whereHas('suspectCases', function ($q) {
            $q->where('pcr_sars_cov_2', 'positive');
        })->where('status', 'Fallecido')->with('suspectCases')->with('demographic')->orderBy('deceased_at')->get();

        return view('lab.suspect_cases.reports.deceased', compact('patients'));
    }


    /*****************************************************/
    /*            REPORTE SISTEMAS EXPERTOS              */
    /*****************************************************/
    public function reporteExpertos()
    {
        $from = Carbon::yesterday();
        $to = Carbon::now();

        $patients = Patient::whereHas('suspectCases', function ($q) use ($from, $to) {
            $q->whereBetween('pcr_sars_cov_2_at', [$from, $to]);
        })->with('suspectCases')->get();

        //dd($patients);
        return response()->json($patients);
    }

    /*****************************************************/
    /*            REPORTE LICENCIA MEDICA                */
    /*****************************************************/
    public function requires_licence()
    {
        $patients = Patient::whereHas('tracing', function ($q) {
                $q->where('status', '>', '0')
                    ->where('requires_licence', 1)
                    ->whereIn('establishment_id', auth()->user()->establishments->pluck('id'));
            })
            ->get();

        return view('lab.suspect_cases.reports.requires_licence', compact('patients'));
    }

    /*****************************************************/
    /*            REPORTE USUARIOS RENDIMIENTO                */
    /*****************************************************/
    public function user_performance(Request $request)
    {
        /* USUARIOS DE MIS ESTABLECIMIENTOS */
        $users = User::whereHas('establishments', function ($q) {
            $q->whereIn('establishment_id', auth()->user()->establishments->pluck('id'));
        })
            ->has('events')
            ->orderBy('name', 'ASC')
            ->get();

        $events = Event::whereDate('event_at', $request->date)
            ->where('user_id', $request->user)
            ->get();

        /* ------------------- CREAR ARRAY DE RESUMEN -----------------------*/
        $events_type = EventType::all();
        foreach ($events_type as $key => $type) {
            $events_resume[$type->name] = 0;
        }
        $events_resume['total'] = 0;

        foreach ($events as $key => $event) {
            $events_resume[$event->type->name] += 1;
            $events_resume['total'] += 1;
        }
        /* ----------------------------------------------------------------- */

        return view('lab.suspect_cases.reports.user_performance', compact('users', 'request', 'events', 'events_resume'));
    }

    public function pendingMoreThanTwoDays()
    {
        $suspectCases = SuspectCase::where('pcr_sars_cov_2', 'pending')
            ->where('reception_at', '<=', Carbon::now()->subDays(2))
            ->get();

        return view('lab.suspect_cases.reports.pending_more_than_two_days', compact('suspectCases'));
    }

    public function suspectCaseByCommune(Request $request)
    {
        if ($from = $request->has('from')) {
            $from = $request->get('from') . ' 00:00:00';
            $to = $request->get('to') . ' 23:59:59';
        } else {
            $from = Carbon::yesterday();
            $to = Carbon::now();
        }

        $communes_ids = Auth()->user()->communes();
        $communes = Commune::whereIn('id', $communes_ids)->get();
        $selectedCommune = $request->get('commune');

        $suspectCases = SuspectCase::whereBetween('pcr_sars_cov_2_at', [$from, $to])
            ->when($selectedCommune, function ($q) use ($selectedCommune) {
                return $q->whereHas('patient', function ($q) use ($selectedCommune) {
                    $q->whereHas('demographic', function ($q) use ($selectedCommune) {
                        $q->where('commune_id', $selectedCommune);
                    });
                });
            })
            ->orderBy('pcr_sars_cov_2_at')
            ->get();

        return view('lab.suspect_cases.reports.suspect_cases_by_commune', compact('suspectCases', 'from', 'to', 'communes', 'selectedCommune'));
    }


    /**
     * Listado de Casos Sospechosos que no han sido
     * recepcionados
     * @return Application|Factory|View
     */
    public function withoutReception()
    {
        $cases = SuspectCase::whereNull('receptor_id')->get();
        return view('lab.suspect_cases.reports.without_reception', compact('cases'));
    }

    public function casesWithoutResults(Request $request)
    {

//        dd($userEstablishments);
        if ($from = $request->has('from')) {
            $from = $request->get('from') . ' 00:00:00';
            $to = $request->get('to') . ' 23:59:59';
        } else {
            $from = Carbon::now();
            $to = Carbon::now();
        }

        if($request->has('from')){
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=examenes_pendientes_recepcionar.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );

//        ->where(function ($query){
//            $query->where('laboratory_id', Auth::user()->laboratory_id)
//                ->orWhereNull('laboratory_id');
//        })

            $userEstablishmentsIds = Auth::user()->establishments->pluck('id')->toArray();

            $filas = null;
            $filas = SuspectCase::where('pcr_sars_cov_2_at', NULL)
            ->whereIn('establishment_id', $userEstablishmentsIds)
            ->whereBetween('created_at', [$from, $to])
            ->latest()
            ->get();


            $columnas = array(
                '#',
                'fecha_muestra',
                'establecimiento',
                'estab. detalle',
                'nombre',
                'identificador',
                'edad',
                'sexo',
                'pcr_sars-cov2',
                'observación',
                'fecha_nacimiento',
                'nacionalidad',
                'correo_electronico',
                'region_toma_muestra',
                'trabajador_de_la_salud',
                'contacto_estrecho',
                'gestante',
                'semanas_gestacion',
                'presenta_sintomatología',
                'fecha_inicio_síntomas',
                'teléfono',
                'recepcionado'
//            'sem',
//            'epivigila',
//            'fecha de resultado',
//            'teléfono',
//            'dirección',
//            'comuna'
            );

            $callback = function() use ($filas, $columnas)
            {
                $file = fopen('php://output', 'w');
                fputs($file, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
                fputcsv($file, $columnas,';');


                foreach($filas as $fila) {



                    fputcsv($file, array(
                        $fila->id,
                        $fila->sample_at,
                        ($fila->establishment)?$fila->establishment->alias: '',
                        $fila->origin,
                        ($fila->patient)?$fila->patient->fullName:'',
                        ($fila->patient)?$fila->patient->Identifier:'',
                        $fila->age,
                        strtoupper($fila->gender[0]),
                        $fila->Covid19,
                        $fila->observation,
                        ($fila->patient) ? $fila->patient->birthday : '',
                        ($fila->patient && $fila->patient->demographic) ? $fila->patient->demographic->nationality : '',
                        ($fila->patient && $fila->patient->demographic) ? $fila->patient->demographic->email : '',
                        'Tarapacá',
                        ($fila->functionary === NULL) ? '' : (($fila->functionary === 1) ? 'Si' : 'No'),
                        ($fila->close_contact === NULL) ? '' : (($fila->close_contact === 1) ? 'Si' : 'No'),
                        ($fila->gestation == 1) ? 'Si' : 'No', //todo
                        $fila->gestation_week,
                        ($fila->symptoms === NULL) ? '' : (($fila->symptoms === 1) ? 'Si' : 'No'),
                        $fila->symptoms_at,
                        ($fila->patient && $fila->patient->demographic) ? $fila->patient->demographic->telephone : '',
                        ($fila->reception_at) ? 'Si' : 'No'
//                    $fila->epidemiological_week,
//                    $fila->epivigila,
//                    $fila->pcr_sars_cov_2_at,
//                    ($fila->patient && $fila->patient->demographic)?$fila->patient->demographic->telephone:'',
//                    ($fila->patient && $fila->patient->demographic)?$fila->patient->demographic->fullAddress:'',
//                    ($fila->patient && $fila->patient->demographic && $fila->patient->demographic->commune)?$fila->patient->demographic->commune->name:'',
                    ),';');
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }



        return view('lab.suspect_cases.reports.cases_without_results', compact('from', 'to'));
    }

    /**
     * Obtiene los casos creados filtrados por establecimiento y fecha de muestra, con códigos de barras
     * @param Request $request
     * @return Application|Factory|View
     */
    public function casesWithBarcodes(Request $request){
        $selectedEstablishment = $request->input('establishment_id');
        $selectedSampleAt = $request->input('sample_at_from');
        $selectedSampleTo = $request->input('sample_at_to');
        $selectedCaseType = $request->input('case_type');

        $suspectCases = null;
        if ($selectedEstablishment and $selectedSampleAt) {
            $suspectCases = SuspectCase::
                where(function ($q) use ($selectedEstablishment) {
                    if ($selectedEstablishment) {
                        $q->where('establishment_id', $selectedEstablishment);
                    }
                })
                ->where(function ($q) use ($selectedSampleAt, $selectedSampleTo) {
                    if ($selectedSampleAt) {
                        $q->where('sample_at','>=', $selectedSampleAt)->where('sample_at','<=', $selectedSampleTo);
                    }
                })
                ->where(function($q) use ($selectedCaseType){
                    if ($selectedCaseType) {
                        $q->where('case_type', $selectedCaseType);
                    }
                })
                ->where('laboratory_id', Auth::user()->laboratory_id)
                ->where('reception_at', NULL)
                ->where('pcr_sars_cov_2', 'pending')
                ->latest()
                ->get();
                //dd($suspectCases);
        }

        $env_communes = array_map('trim',explode(",",env('COMUNAS')));
        $establishments = Establishment::whereIn('commune_id',$env_communes)->orderBy('name','ASC')->get();

        return view('lab.suspect_cases.reports.cases_with_barcodes', compact('suspectCases', 'establishments', 'selectedEstablishment', 'selectedSampleAt','selectedSampleTo', 'selectedCaseType'));

    }

    public function casesByIdsIndex()
    {
//        $externalLabs = Laboratory::where('external', 1)->get();
        return view('lab.suspect_cases.reports.cases_by_ids_index');
    }

    public function exportExcelByCasesIds(Request $request){

                $ids = $request->get('suspectCasesId');
        $ids = explode(' ', $ids);
        $id_lab_report = $request->get('id_lab_report');

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=examenes_pendientes_recepcionar.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $filas = SuspectCase::whereIn('id', $ids)
            ->latest()
            ->get();


        $columnas = array(
            'run',
            'dv',
            'name',
            'fathers_family',
            'mothers_family',
            'gender',
            'birthday',
            'status',
            'street_type',
            'address',
            'number',
            'department',
            'city',
            'suburb',
            'commune_id',
            'region_id',
            'nationality',
            'telephone',
            'email',
            'laboratory_id',
            'sample_type',
            'sample_at',
            'recepcion_at',
            'pcr_sars_cov_2_at',
            'pcr_sars_cov_2',
            'name',
            'origin',
            'run_medic',
            'symptoms',
            'symptoms_at',
            'gestation',
            'gestation_week',
            'index_',
            'functionary',
            'observation',
            'epivigila',
        );

        $callback = function() use ($filas, $columnas, $id_lab_report)
        {
            $file = fopen('php://output', 'w');
            fputs($file, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
            fputcsv($file, $columnas,';');
            foreach($filas as $fila) {
                fputcsv($file, array(
                    ($fila->patient->run) ? $fila->patient->run : $fila->patient->other_identification,
                    $fila->patient->dv,
                    $fila->patient->name,
                    $fila->patient->fathers_family,
                    $fila->patient->mothers_family,
                    $fila->patient->genderEsp,
                    $fila->patient->birthday,
                    $fila->patient->status,
                    ($fila->patient->demographic) ? $fila->patient->demographic->street_type : '',
                    ($fila->patient->demographic) ? $fila->patient->demographic->address : '',
                    ($fila->patient->demographic) ? $fila->patient->demographic->number : '',
                    ($fila->patient->demographic) ? $fila->patient->demographic->department : '',
                    ($fila->patient->demographic) ? $fila->patient->demographic->city : '',
                    ($fila->patient->demographic) ? $fila->patient->demographic->suburb : '',
                    ($fila->patient->demographic) ? $fila->patient->demographic->commune_id : '',
                    ($fila->patient->demographic) ? $fila->patient->demographic->region_id : '',
                    ($fila->patient->demographic) ? $fila->patient->demographic->nationality : '',
                    ($fila->patient->demographic) ? $fila->patient->demographic->telephone : '',
                    ($fila->patient->demographic) ? $fila->patient->demographic->email : '',
                    $id_lab_report,
                    $fila->sample_type,
                    $fila->sample_at,
                    $fila->reception_at,
                    $fila->pcr_sars_cov_2_at,
                    $fila->covid19,
                    $fila->establishment->name,
                    $fila->origin,
                    $fila->run_medic,
                    $fila->symptomEsp,
                    $fila->symptoms_at,
                    $fila->gestationEsp,
                    $fila->gestation_week,
                    '',
                    $fila->functionaryEsp,
                    $fila->observation,
                    $fila->epivigila,
                ),';');
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }


    public function allRapidTests()
    {


        $rapidtests = RapidTest::all();
        return view('lab.suspect_cases.reports.all_rapid_tests', compact('rapidtests'));
    }

    public function integrationHetgMonitorPendings(Request $request)
    {
      $status = null;
      if ($request->status) {
        $status = $request->status;
      }else{
        $status = "case_not_found";
      }

      // dd($status);

      $hl7ResultMessages = Hl7ResultMessage::whereNotNull('status')
                                            ->when($status != null, function ($q) use ($status) {
                                                return $q->where('status',$status)
                                                ->where('created_at', '>', '2022-03-01 00:00:00');
                                            })
                                            ->when($status != "assigned_to_case" && $status != "monitor_error", function ($q) use ($status) {
                                                return $q->whereNotNull('pdf_file');
                                            })
                                            // ->whereNotNull('pdf_file')
                                            ->orderBy('observation_datetime','DESC')
                                            ->get();

      return view('lab.suspect_cases.reports.integration_hetg_monitor_pendings',compact('hl7ResultMessages','request'));
    }

    public function integrationHetgMonitorPendingsDetails(Hl7ResultMessage $hl7ResultMessage, Request $request)
    {

      $suspectCases = null;
      $cases = null;
      if ($request->text != null && $hl7ResultMessage->status == "case_not_found") {
        $collection = collect(['positivos', 'negativos', 'pendientes', 'rechazados', 'indeterminados']);
        $filtro = collect([]);
        $collection->each(function ($item, $key) use ($request, $filtro){
                    switch ($item) {
                case "positivos":
                    $request->get('positivos')=="on"?$filtro->push('positive'):true;
                    break;
                case "negativos":
                    $request->get('negativos')=="on"?$filtro->push('negative'):true;
                    break;
                case "pendientes":
                    $request->get('pendientes')=="on"?$filtro->push('pending'):true;
                    break;
                case "rechazados":
                    $request->get('rechazados')=="on"?$filtro->push('rejected'):true;
                    break;
                case "indeterminados":
                    $request->get('indeterminados')=="on"?$filtro->push('undetermined'):true;
                    break;
            }
        });

        $patients = Patient::getPatientsBySearch($request->get('text'));

         DB::connection()->getPdo()->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
         $suspectCases = SuspectCase::getCaseByPatient($patients)
                             ->latest('id')
                             ->where('laboratory_id',1) //solo laboratorio hospital
                             ->whereIn('pcr_sars_cov_2',$filtro)
                             ->whereNotNull('reception_at')
                             ->paginate(200);
         DB::connection()->getPdo()->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      }
      // dd($suspectCases);

      return view('lab.suspect_cases.reports.integration_hetg_monitor_pendings_details',compact('hl7ResultMessage','request','suspectCases'));
    }

    // public function Hl7ResultMessageSuspectCaseAsignation(Hl7ResultMessage $hl7ResultMessage, SuspectCase $suspectCase, Request $request)
    // {
    //   // if ($hl7ResultMessage->observation_value == "Negativo") {
    //   //     $pcrSarsCov2 = "negative";
    //   // }
    //   // if ($hl7ResultMessage->observation_value == "Positivo") {
    //   //     $pcrSarsCov2 = "positive";
    //   // }
    //   // if ($hl7ResultMessage->observation_value == "Rechazado") {
    //   //     $pcrSarsCov2 = "rejected";
    //   // }
    //   // if ($hl7ResultMessage->observation_value == "Indeterminado") {
    //   //     $pcrSarsCov2 = "undetermined";
    //   // }
    //   //
    //   // $sucesfulStore = Storage::put('suspect_cases/' . $suspectCase->id . '.pdf' , $hl7ResultMessage->pdf_file);
    //   //
    //   // if ($sucesfulStore) {
    //   //   $suspectCase->pcr_sars_cov_2_at = $hl7ResultMessage->observation_datetime;
    //   //   $suspectCase->pcr_sars_cov_2 = $pcrSarsCov2;
    //   //   $suspectCase->hl7_result_message_id = $hl7ResultMessage->id;
    //   //   $suspectCase->file = 1;
    //   //   $suspectCase->save();
    //   //
    //   //   foreach ($hl7ResultMessage->suspectCases as $key => $suspectCase_item) {
    //   //     if ($suspectCase_item->id != $suspectCase->id) {
    //   //       $suspectCase_item->hl7_result_message_id = null;
    //   //       $suspectCase_item->save();
    //   //     }
    //   //   }
    //   //
    //   //   $hl7ResultMessage->status = "assigned_to_case";
    //   //   $hl7ResultMessage->pdf_file = null;
    //   //   $hl7ResultMessage->save();
    //   //
    //   //   //se intenta subir a PNTM
    //   //   $this->addSuspectCaseResult($suspectCase, $hl7ResultMessage);
    //   //
    //   //   session()->flash('success', 'Se asignó muestra ' . $suspectCase->id . " a caso pendiente " . $hl7ResultMessage->id);
    //   //   return redirect()->route('lab.suspect_cases.reports.integration_hetg_monitor_pendings');
    //   //
    //   // }else{
    //   //   session()->flash('error', "Error al obtener archivo pdf");
    //   //   return redirect()->route('lab.suspect_cases.reports.integration_hetg_monitor_pendings');
    //   // }
    //
    //   if ($this->addSuspectCaseResult($suspectCase, $hl7ResultMessage)) {
    //       $hl7ResultMessage->status = "assigned_to_case";
    //       $hl7ResultMessage->pdf_file = null;
    //       $hl7ResultMessage->save();
    //
    //       session()->flash('success', 'Se asignó muestra ' . $suspectCase->id . " a caso pendiente " . $hl7ResultMessage->id);
    //       return redirect()->route('lab.suspect_cases.reports.integration_hetg_monitor_pendings');
    //   }else{
    //     session()->flash('error', "Error--");
    //     return redirect()->route('lab.suspect_cases.reports.integration_hetg_monitor_pendings');
    //   }
    //
    //
    // }

}
