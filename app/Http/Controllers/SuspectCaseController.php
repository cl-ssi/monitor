<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;

use App\SuspectCase;
use App\Patient;
use App\Demographic;
use App\File;
use App\User;
use App\EstablishmentUser;
use App\Region;
use App\Commune;
use App\Laboratory;
use App\Establishment;
use App\ReportBackup;
use App\SampleOrigin;
use App\Country;
use App\Tracing\Tracing;
use Carbon\CarbonPeriod;
use Carbon\Carbon;
use App\Mail\NewPositive;
use App\Mail\NewNegative;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SuspectCasesExport;
use App\Exports\HetgSuspectCasesExport;
use App\Exports\UnapSuspectCasesExport;
use App\Exports\MinsalSuspectCasesExport;
use App\Exports\SeremiSuspectCasesExport;

class SuspectCaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(request $request, Laboratory $laboratory)
    {
        //$lab_id = $request->input('lab_id');

        if ($request->get('positivos') == "on") {
            $positivos = "positive";
        } else {$positivos = NULL;}

        if ($request->get('negativos') == "on") {
            $negativos = "negative";
        } else {$negativos = NULL;}

        if ($request->get('pendientes') == "on") {
            $pendientes = "pending";
        } else{$pendientes = NULL;}

        if ($request->get('rechazados') == "on") {
            $rechazados = "rejected";
        } else{$rechazados = NULL;}

        if ($request->get('indeterminados') == "on") {
            $indeterminados = "undetermined";
        }else{$indeterminados = NULL;}

        $text = $request->get('text');

        if($laboratory->id) {
            //$laboratory = Laboratory::find($lab->id);
            $suspectCasesTotal = SuspectCase::where('laboratory_id',$laboratory->id)->latest('id')->get();

            $suspectCases = SuspectCase::latest('id')
                                      ->where('laboratory_id',$laboratory->id)
                                      ->whereHas('patient', function($q) use ($text){
                                              $q->Where('name', 'LIKE', '%'.$text.'%')
                                                ->orWhere('fathers_family','LIKE','%'.$text.'%')
                                                ->orWhere('mothers_family','LIKE','%'.$text.'%')
                                                ->orWhere('run','LIKE','%'.$text.'%');
                                      })
                                      ->whereNotNull('laboratory_id')
                                      ->whereIn('pscr_sars_cov_2',[$positivos, $negativos, $pendientes, $rechazados, $indeterminados])
                                      ->paginate(200);//->appends(request()->query());
        }
        else {
            $laboratory = null;
            $suspectCasesTotal = SuspectCase::whereNotNull('laboratory_id')->latest('id')->get();

            $suspectCases = SuspectCase::latest('id')
                                      ->whereHas('patient', function($q) use ($text){
                                              $q->Where('name', 'LIKE', '%'.$text.'%')
                                                ->orWhere('fathers_family','LIKE','%'.$text.'%')
                                                ->orWhere('mothers_family','LIKE','%'.$text.'%')
                                                ->orWhere('run','LIKE','%'.$text.'%');
                                      })
                                      ->whereNotNull('laboratory_id')
                                      ->whereIn('pscr_sars_cov_2',[$positivos, $negativos, $pendientes, $rechazados, $indeterminados])
                                      ->paginate(200);//->appends(request()->query());
        }
        return view('lab.suspect_cases.index', compact('suspectCases','request','suspectCasesTotal','laboratory'));
    }

    /**
     * Muestra exámenes asociados al establishment de usuario actual.
     * @param Request $request
     * @param Laboratory $laboratory
     * @return Application|Factory|View
     */
    public function ownIndex(request $request, Laboratory $laboratory)
    {
        $searchText = $request->get('text');
        $arrayFilter = (empty($request->filter)) ? array() : $request->filter;

        $suspectCasesTotal = SuspectCase::whereIn('establishment_id', Auth::user()->establishments->pluck('id'))->get();

        $suspectCases = SuspectCase::whereIn('establishment_id', Auth::user()->establishments->pluck('id'))
            ->patientTextFilter($searchText)
            ->whereIn('pscr_sars_cov_2', $arrayFilter)
            ->paginate(200);

        return view('lab.suspect_cases.ownIndex', compact('suspectCases', 'arrayFilter', 'searchText', 'laboratory', 'suspectCasesTotal'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $external_labs = Laboratory::where('external',1)->orderBy('name')->get();
        $establishments = Establishment::orderBy('name','ASC')->get();

        /* FIX codigo duro */
        $env_communes = array_map('trim',explode(",",env('COMUNAS')));
        $establishments = Establishment::whereIn('commune_id',$env_communes)->orderBy('name','ASC')->get();

        $sampleOrigins = SampleOrigin::orderBy('alias')->get();
        return view('lab.suspect_cases.create',compact('sampleOrigins','establishments','external_labs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function admission()
    {
        $regions = Region::orderBy('id','ASC')->get();
        $communes = Commune::orderBy('id','ASC')->get();
        $env_communes = array_map('trim',explode(",",env('COMUNAS')));
        $establishments = Establishment::whereIn('commune_id',$env_communes)->where('name','<>','Otros')->orderBy('name','ASC')->get();

        $sampleOrigins = SampleOrigin::orderBy('alias')->get();
        return view('lab.suspect_cases.admission',compact('sampleOrigins','regions', 'communes','establishments'));
    }


    public function reception(Request $request, SuspectCase $suspectCase)
    {
        /* Recepciona en sistema */
        $suspectCase->laboratory_id = Auth::user()->laboratory->id;
        $suspectCase->receptor_id   = Auth::id();
        $suspectCase->reception_at  = date('Y-m-d H:i:s');
        $suspectCase->save();

        session()->flash('info', 'Se ha recepcionada la muestra: '
            . $suspectCase->id . ' en laboratorio: '
            . Auth::user()->laboratory->name);

        return redirect()->back();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        dd($request);
        if ($request->id == null) {
            $patient = new Patient($request->All());
        } else {
            $patient = Patient::find($request->id);
            $patient->fill($request->all());
        }
        $patient->save();

        $suspectCase = new SuspectCase($request->All());
        $suspectCase->epidemiological_week = Carbon::createFromDate($suspectCase->sample_at->format('Y-m-d'))->add(1, 'days')->weekOfYear;

        /* Recepcionar si soy del laboratorio */
        $suspectCase->laboratory_id = Auth::user()->laboratory_id;
        $suspectCase->receptor_id = Auth::id();
        $suspectCase->user_id = Auth::id();
        $suspectCase->run_medic = $request->run_medic_s_dv . "-" . $request->run_medic_dv;

        $suspectCase->reception_at = date('Y-m-d H:i:s');

        if(!$request->input('pscr_sars_cov_2')) {
            $suspectCase->pscr_sars_cov_2 = 'pending';
        }

        if($request->input('pscr_sars_cov_2_at')){
            $suspectCase->pscr_sars_cov_2_at = $request->input('pscr_sars_cov_2_at').' '.date('H:i:s');
        }

        $suspectCase->sample_at = $request->input('sample_at').' '.date('H:i:s');

        $patient->suspectCases()->save($suspectCase);

        //guarda archivos
        if ($request->hasFile('forfile')) {
            foreach ($request->file('forfile') as $file) {
                $filename = $file->getClientOriginalName();
                $fileModel = new File;
                $fileModel->file = $file->store('files');
                $fileModel->name = $filename;
                $fileModel->suspect_case_id = $suspectCase->id;
                $fileModel->save();
            }
        }

        if (env('APP_ENV') == 'production') {
            if ($suspectCase->pscr_sars_cov_2 == 'positive') {
                $emails  = explode(',', env('EMAILS_ALERT'));
                $emails_bcc  = explode(',', env('EMAILS_ALERT_BCC'));
                Mail::to($emails)->bcc($emails_bcc)->send(new NewPositive($suspectCase));
            }
        }

        //$log = new Log();
        //$log->old = $suspectCase;
        //$log->new = $suspectCase;
        //$log->save();

        session()->flash('success', 'Se ha creado el caso número: <h3>' . $suspectCase->id . '</h3>');
        return redirect()->route('lab.suspect_cases.index',$suspectCase->laboratory_id);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeAdmission(Request $request)
    {

        /* Si existe el paciente lo actualiza, si no, crea uno nuevo */
        if ($request->id == null) {
            $patient = new Patient($request->All());
        } else {
            $patient = Patient::find($request->id);
            $patient->fill($request->all());
        }
        $patient->save();

        $suspectCase = new SuspectCase($request->All());
        $suspectCase->user_id = Auth::id();
        $suspectCase->run_medic = $request->run_medic_s_dv . "-" . $request->run_medic_dv;

        /* Calcula la semana epidemiológica */
        $suspectCase->epidemiological_week = Carbon::createFromDate($suspectCase->sample_at->format('Y-m-d'))
                                                    ->add(1, 'days')->weekOfYear;

        /* Marca como pendiente el resultado, no viene en el form */
        $suspectCase->pscr_sars_cov_2 = 'pending';

        /* Si viene la fecha de nacimiento entonces calcula la edad y la almaceno en suspectCase */
        if($request->input('birthday')) {
            $suspectCase->age = $patient->age;
        }

        /* Si se crea el caso por alguien con laboratorio asignado */
        /* La muestra se recepciona inmediatamente */
        if(Auth::user()->laboratory_id) {
            $suspectCase->laboratory_id = Auth::user()->laboratory_id;
            $suspectCase->reception_at  = date('Y-m-d H:i:s');
            $suspectCase->receptor_id   = Auth::id();
        }

        /* Guarda el caso sospecha */
        $patient->suspectCases()->save($suspectCase);

        if($patient->demographic) {
            $patient->demographic->fill($request->all());
            $patient->demographic->save();
        }
        else {
            $demographic = new Demographic($request->All());
            $demographic->patient_id = $patient->id;
            $demographic->save();
        }

        session()->flash('success', 'Se ha creado el caso número: <h3>'
            . $suspectCase->id. ' <a href="' . route('lab.suspect_cases.notificationForm',$suspectCase)
            . '">Imprimir Formulario</a></h3>');

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SuspectCase  $suspectCase
     * @return \Illuminate\Http\Response
     */
    public function show(SuspectCase $suspectCase)
    {
        return view('lab.suspect_cases.show', compact('suspectCase'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SuspectCase  $suspectCase
     * @return \Illuminate\Http\Response
     */
    public function edit(SuspectCase $suspectCase)
    {
        $external_labs = Laboratory::where('external',1)->orderBy('name')->get();
        $local_labs = Laboratory::where('external',0)->orderBy('name')->get();

        $establishments = Establishment::whereIn('commune_id',explode(',',env('COMUNAS')))
                                        ->orderBy('name','ASC')->get();

        $sampleOrigins = SampleOrigin::orderBy('alias')->get();

        return view('lab.suspect_cases.edit',
            compact('suspectCase','external_labs','local_labs','establishments','sampleOrigins')
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SuspectCase  $suspectCase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SuspectCase $suspectCase)
    {
        $old_pcr = $suspectCase->pscr_sars_cov_2;

        $suspectCase->fill($request->all());

        $suspectCase->epidemiological_week = Carbon::createFromDate(
            $suspectCase->sample_at->format('Y-m-d'))->add(1, 'days')->weekOfYear;

        /* Setar el validador */
        if ($old_pcr == 'pending' and $suspectCase->pscr_sars_cov_2 != 'pending') {
            $suspectCase->validator_id = Auth::id();
        }

        $suspectCase->save();

        /* guarda archivos FIX: pendiente traspasar a sólo un archivo */
        if ($request->hasFile('forfile')) {
            foreach ($request->file('forfile') as $file) {
                $filename = $file->getClientOriginalName();
                $fileModel = new File;
                $fileModel->file = $file->store('files');
                $fileModel->name = $filename;
                $fileModel->suspect_case_id = $suspectCase->id;
                $fileModel->save();
            }
        }

        if (env('APP_ENV') == 'production') {
            if ($old_pcr == 'pending' and $suspectCase->pscr_sars_cov_2 == 'positive') {
                $emails  = explode(',', env('EMAILS_ALERT'));
                $emails_bcc  = explode(',', env('EMAILS_ALERT_BCC'));
                Mail::to($emails)->bcc($emails_bcc)->send(new NewPositive($suspectCase));
            }
            /* TODO: Si el resultado es negativo y el usuario tiene email, enviar resultado al usuario */
            if($old_pcr == 'pending' && ($suspectCase->pscr_sars_cov_2 == 'negative' ||
                                          $suspectCase->pscr_sars_cov_2 == 'undetermined' ||
                                          $suspectCase->pscr_sars_cov_2 == 'rejected') &&
                $suspectCase->patient->demographic != NULL){
                if($suspectCase->patient->demographic->email != NULL){
                    $email  = $suspectCase->patient->demographic->email;
                    Mail::to($email)->send(new NewNegative($suspectCase));
                }
            }
        }

        /* Crea un TRACING si el resultado es positivo */
        if ($old_pcr == 'pending' and $suspectCase->pscr_sars_cov_2 == 'positive') {
            $tracing                    = new Tracing();
            $tracing->patient_id        = $suspectCase->patient_id;
            $tracing->user_id           = $suspectCase->user_id;
            $tracing->index             = 1;
            $tracing->establishment_id  = $suspectCase->establishment_id;
            $tracing->functionary       = $suspectCase->functionary;
            $tracing->gestation         = $suspectCase->gestation;
            $tracing->gestation_week    = $suspectCase->gestation_week;
            $tracing->next_control_at   = $suspectCase->pscr_sars_cov_2_at->add(1,'day');
            $tracing->quarantine_start_at = $suspectCase->pscr_sars_cov_2_at;
            $tracing->quarantine_end_at = $suspectCase->pscr_sars_cov_2_at->add(14,'days');
            $tracing->observations      = $suspectCase->observation;
            $tracing->status            = ($suspectCase->patient->status == 'Fallecido') ? 0:1;
            switch ($suspectCase->symptoms) {
                case 'Si':
                    $tracing->symptoms = 1; break;
                case 'No':
                    $tracing->symptoms = 0; break;
                default:
                    $tracing->symptoms = null; break;
            }
            $tracing->save();
        }


        return redirect()->route('lab.suspect_cases.index',$suspectCase->laboratory_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SuspectCase  $suspectCase
     * @return \Illuminate\Http\Response
     */
    public function destroy(SuspectCase $suspectCase)
    {
        //$log = new Log();
        //$log->old = clone $suspectCase;
        //$log->new = $suspectCase->setAttribute('suspect_case', 'delete');
        //$log->save();

        $suspectCase->delete();

        return redirect()->route('lab.suspect_cases.index');
    }

    public function fileDelete(File $file)
    {
        // $log = new Log();
        // $log->old =  clone $file;
        // $log->new =  $file->setAttribute('suspect_case', 'delete');
        // $log->save();

        /* TODO: implementar auditable en file delete  */
        Storage::delete($file->file);
        $file->delete();

        return redirect()->back();
    }


    /**
     * Search suspectCase by ID.
     *
     * @param  \App\SuspectCase  $suspectCase
     * @return \Illuminate\Http\Response
     */
    public function search_id(Request $request)
    {
        $suspectCase = SuspectCase::find($request->input('id'));
        if($suspectCase) return redirect()->route('lab.suspect_cases.edit', $suspectCase);
        else {
            session()->flash('warning', 'No se ha encontrado el exámen ID: <h3>' . $request->input('id') . '</h3>');
            return redirect()->back();
        }
    }


    public function historical_report(Request $request)
    {
        if($request->has('date')){
            $date = $request->get('date');
        } else {
            $date = Carbon::now();
        }

        $reportBackup = ReportBackup::whereDate('created_at',$date)->get();

        if($reportBackup->count() <> 0){
            if($reportBackup->first()->id <= 10){
                $html = json_decode($reportBackup->first()->data, true);
            }else{
                $html = $reportBackup->first()->data;
            }

            $begin = strpos($html, '<main class="py-4 container">')+29;
            $v1 = substr($html, $begin, 999999);
            $end   = strpos($v1, '</main>')-7;
            $main = substr($v1, 0, $end);

            $begin = strpos($html, '<head>')+6;
            $v1 = substr($html, $begin, 999999);
            $end   = strpos($v1, '</head>');
            $head = substr($v1, 0, $end);
        }else{
            $head="";
            $main="";
        }

        return view('lab.suspect_cases.reports.historical_report', compact('head','main','date'));
    }

    public function diary_by_lab_report(Request $request)
    {
        //FIRST CASE
        $beginExamDate = SuspectCase::orderBy('sample_at')->first()->sample_at;
        $laboratories = Laboratory::all();

        $periods = CarbonPeriod::create($beginExamDate, now());

        $periods_count = $periods->count();

        // NUEVO CODIGO
        foreach ($periods as $key => $period) {
            foreach ($laboratories as $lab) {
          		$cases_by_days[$period->format('d-m-Y')]['laboratories'][$lab->name] = 0;
          	}
            $cases_by_days[$period->format('d-m-Y')]['cases'] = 0;
        }

        $suspectCases = SuspectCase::All();

        foreach ($suspectCases as $suspectCase) {
          $total_cases_by_days['cases'] = 0;

        }

        //CARGA ARRAY CASOS
        foreach ($suspectCases as $suspectCase) {
          if($suspectCase->pscr_sars_cov_2_at != null){
            if($suspectCase->external_laboratory){
              $cases_by_days[$suspectCase->pscr_sars_cov_2_at->format('d-m-Y')]['laboratories'][$suspectCase->external_laboratory] += 1;
            }
            else{
              $cases_by_days[$suspectCase->pscr_sars_cov_2_at->format('d-m-Y')]['laboratories'][$suspectCase->laboratory->name] += 1;
            }
            $cases_by_days[$suspectCase->pscr_sars_cov_2_at->format('d-m-Y')]['cases'] += 1;
            $total_cases_by_days['cases'] += 1;
          }
        }
        return view('lab.suspect_cases.reports.diary_by_lab_report', compact('cases_by_days', 'total_cases_by_days'));
    }

    public function diary_lab_report(Request $request)
    {
        $beginExamDate = SuspectCase::orderBy('sample_at')->first()->sample_at;

        $periods = CarbonPeriod::create($beginExamDate, now());

        $periods_count = $periods->count();

        foreach ($periods as $key => $period) {
            $cases_by_days[$period->format('d-m-Y')]['cases'] = 0;
            $cases_by_days[$period->format('d-m-Y')]['negative'] = 0;
            $cases_by_days[$period->format('d-m-Y')]['positive'] = 0;
            $cases_by_days[$period->format('d-m-Y')]['rejected'] = 0;
            $cases_by_days[$period->format('d-m-Y')]['undetermined'] = 0;
            $cases_by_days[$period->format('d-m-Y')]['pending'] = 0;
            $cases_by_days[$period->format('d-m-Y')]['procesing'] = 0;

        }

        $suspectCases = SuspectCase::whereNotNull('laboratory_id')->get();

        foreach ($suspectCases as $suspectCase) {
          $total_cases_by_days['cases'] = 0;
          $total_cases_by_days[$suspectCase->pscr_sars_cov_2] = 0;
        }



        //CARGA ARRAY CASOS
        foreach ($suspectCases as $suspectCase) {

          $cases_by_days[$suspectCase->sample_at->format('d-m-Y')]['cases'] += 1;
          if($suspectCase->reception_at != null){
            $cases_by_days[$suspectCase->sample_at->format('d-m-Y')][$suspectCase->pscr_sars_cov_2] += 1;
          }
          if($suspectCase->pscr_sars_cov_2_at != null){
            $cases_by_days[$suspectCase->pscr_sars_cov_2_at->format('d-m-Y')]['procesing'] += 1;
          }

          $total_cases_by_days['cases'] += 1;
          $total_cases_by_days[$suspectCase->pscr_sars_cov_2] += 1;
        }

        return view('lab.suspect_cases.reports.diary_lab_report', compact('cases_by_days', 'total_cases_by_days'));
    }

    public function estadistico_diario_covid19(Request $request)
    {
        $yesterday = Carbon::now()->subDays(1)->format('Y-m-d 21:00');
        $now = Carbon::now()->format('Y-m-d 21:00');
        //dd($yesterday, $now);

        $array = array();
        $cases = SuspectCase::whereBetween('created_at',[$yesterday,$now])
                            ->where('external_laboratory',NULL)
                            ->whereNotNull('laboratory_id')
                            ->get();
        //dd($cases);
        foreach ($cases as $key => $case) {
          $array[$case->laboratory->name]['muestras_en_espera'] = 0;
          $array[$case->laboratory->name]['muestras_recibidas'] = 0;
          $array[$case->laboratory->name]['muestras_procesadas'] = 0;
          $array[$case->laboratory->name]['muestras_positivas'] = 0;
          $array[$case->laboratory->name]['muestras_procesadas_acumulados'] = 0;
          $array[$case->laboratory->name]['muestras_procesadas_positivo'] = 0;
          $array[$case->laboratory->name]['commune'] = '';
        }

        foreach ($cases as $key => $case) {
          if($case->pscr_sars_cov_2 == "pending"){
            $array[$case->laboratory->name]['muestras_en_espera'] += 1;
          }
          $array[$case->laboratory->name]['muestras_recibidas'] += 1;
          if($case->pscr_sars_cov_2 != "pending" || $case->pscr_sars_cov_2 != "rejected"){
            $array[$case->laboratory->name]['muestras_procesadas'] += 1;
          }
          if($case->pscr_sars_cov_2 == "positive"){
            $array[$case->laboratory->name]['muestras_positivas'] += 1;
          }

          $array[$case->laboratory->name]['muestras_procesadas_acumulados'] = SuspectCase::where('external_laboratory',NULL)
                                                                                         ->where('laboratory_id',$case->laboratory_id)
                                                                                         ->where('pscr_sars_cov_2','<>','pending')
                                                                                         ->where('pscr_sars_cov_2','<>','rejected')
                                                                                         ->count();

          $array[$case->laboratory->name]['muestras_procesadas_positivo'] = SuspectCase::where('external_laboratory',NULL)
                                                                                         ->where('laboratory_id',$case->laboratory_id)
                                                                                         ->where('pscr_sars_cov_2','positive')
                                                                                         ->count();
          $array[$case->laboratory->name]['commune'] = $case->laboratory->commune->name;
        }

        //dd($array);

        return view('lab.suspect_cases.reports.estadistico_diario_covid19', compact('array','yesterday', 'now'));
    }



    public function download(File $file)
    {
        return Storage::response($file->file, mb_convert_encoding($file->name, 'ASCII'));
    }

    public function login($access_token = null)
    {
        if ($access_token) {
            return redirect()->route('lab.result')->with('access_token', $access_token);
        }
    }

    public function result()
    {
      // dd("");
        if (env('APP_ENV') == 'production') {
            $access_token = session()->get('access_token');
            $url_base = "https://www.claveunica.gob.cl/openid/userinfo/";
            $response = Http::withToken($access_token)->post($url_base);
            $user_cu = json_decode($response);

            $user = new User();
            $user->id = $user_cu->RolUnico->numero;
            $user->dv = $user_cu->RolUnico->DV;
            $user->name = implode(' ', $user_cu->name->nombres);
            $user->fathers_family = $user_cu->name->apellidos[0];
            $user->mothers_family = $user_cu->name->apellidos[1];
            $user->email = $user_cu->email;
        } elseif (env('APP_ENV') == 'local') {
            $user = new User();
            $user->id = 16055586;
            $user->dv = 6;
            $user->name = "maria angela";
            $user->fathers_family = "family";
            $user->mothers_family = "mother";
            $user->email = "email@email.com";
        }

        // dd($user);

        Auth::login($user);
        $patient = Patient::where('run', $user->id)->first();
        return view('lab.result', compact('patient'));
    }

    public function print(SuspectCase $suspect_case)
    {
        //$case = SuspectCase::find(1);
        $case = $suspect_case;

        $pdf = \PDF::loadView('lab.results.result', compact('case'));
        return $pdf->stream();
    }


    public function reception_inbox(Request $request)
    {
        $selectedEstablishment = $request->input('establishment_id');

        $suspectCases = SuspectCase::whereNull('laboratory_id')
            ->search($request->input('search'))
            ->where(function($q) use($selectedEstablishment){
                if($selectedEstablishment){
                    $q->where('establishment_id', $selectedEstablishment);
                }
            })
            ->latest()
            ->paginate(200);

        $env_communes = array_map('trim',explode(",",env('COMUNAS')));
        $establishments = Establishment::whereIn('commune_id',$env_communes)->orderBy('name','ASC')->get();

        return view('lab.suspect_cases.reception_inbox', compact('suspectCases', 'establishments', 'selectedEstablishment'));
    }

    public function exportExcel($cod_lab){
        return Excel::download(new SuspectCasesExport($cod_lab), 'lista-examenes.xlsx');
    }

    public function exportMinsalExcel($laboratory, Request $request)
    {
        //dd($from);

        if($from = $request->has('from')){
            $from = $request->get('from'). ' 21:00:00';
            $to = $request->get('to'). ' 20:59:59';
        }else{
            $from = date("Y-m-d 21:00:00", time() - 60 * 60 * 24);
            $to = date("Y-m-d 20:59:59");
        }

        // $from = $request->get('from'). ' 21:00:00';
        // $to = $request->get('to'). ' 20:59:59';
        //dd($request->get('from'));
        // switch ($cod_lab) {
        //     case '1':
        //         $nombre_lab = 'HETG';
        //         break;
        //     case '2':
        //         $nombre_lab = 'UNAP';
        //         break;
        // }

        //dd($nombre_lab);

        //return Excel::download(new MinsalSuspectCasesExport($cod_lab, $nombre_lab), 'reporte-minsal.xlsx');
        //dd($from);
        return Excel::download(new MinsalSuspectCasesExport($laboratory, $from, $to), 'reporte-minsal-desde-'.$from.'-hasta-'.$to.'.xlsx');
    }

    public function exportSeremiExcel($cod_lab = null)
    {
        switch ($cod_lab) {
            case '1':
                $nombre_lab = 'HETG';
                break;
            case '2':
                $nombre_lab = 'UNAP';
                break;
        }

        return Excel::download(new SeremiSuspectCasesExport($cod_lab, $nombre_lab), 'reporte-seremi.xlsx');
    }

    public function notificationForm(SuspectCase $suspectCase)
    {
        $user = auth()->user();
        return view('lab.suspect_cases.notification_form', compact('suspectCase', 'user'));
    }
}
