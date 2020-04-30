<?php

namespace App\Http\Controllers;

use App\SuspectCase;
use App\Patient;
use App\Log;
use App\File;
use App\User;
use App\ReportBackup;
use Carbon\Carbon;
use App\Mail\NewPositive;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
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
    public function index(request $request)
    {
      if ($request->get('positivos') == "on") {
        $positivos = "positive";
      }else{$positivos = NULL;}

      if ($request->get('negativos') == "on") {
        $negativos = "negative";
      }else{$negativos = NULL;}

      if ($request->get('pendientes') == "on") {
        $pendientes = "pending";
      }else{$pendientes = NULL;}

      if ($request->get('rechazados') == "on") {
        $rechazados = "rejected";
      }else{$rechazados = NULL;}

      if ($request->get('indeterminados') == "on") {
        $indeterminados = "undetermined";
      }else{$indeterminados = NULL;}

      $text = $request->get('text');

      $suspectCases = SuspectCase::whereHas('patient', function($q) use ($text){
                                          $q->Where('name', 'LIKE', '%'.$text.'%');
                                  })
                                  ->whereIn('pscr_sars_cov_2',[$positivos, $negativos, $pendientes, $rechazados, $indeterminados])
                                  ->paginate(50);//->appends(request()->query());

        return view('lab.suspect_cases.index', compact('suspectCases','request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('lab.suspect_cases.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->id == null) {
            $patient = new Patient($request->All());
        } else {
            $patient = Patient::find($request->id);
            $patient->fill($request->all());
        }
        $patient->save();

        $suspectCase = new SuspectCase($request->All());
        $suspectCase->epidemiological_week = Carbon::createFromDate($suspectCase->sample_at->format('Y-m-d'))->add(1, 'days')->weekOfYear;
        $suspectCase->laboratory_id = Auth::user()->laboratory->id;
        if(!$request->input('pscr_sars_cov_2')) {
            $suspectCase->pscr_sars_cov_2 = 'pending';
        }
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

        $log = new Log();
        //$log->old = $suspectCase;
        $log->new = $suspectCase;
        $log->save();

        switch($suspectCase->laboratory_id) {
            case(1): $ruta = 'lab.suspect_cases.hetg'; break;
            case(2): $ruta = 'lab.suspect_cases.unap'; break;
            default: $ruta = 'lab.suspect_cases.index'; break;
        }

        session()->flash('success', 'Se ha creado el caso número: <h3>' . $suspectCase->id . '</h3>');
        return redirect()->route($ruta);
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
        return view('lab.suspect_cases.edit', compact('suspectCase'));
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
        $log = new Log();
        $log->old = clone $suspectCase;

        $suspectCase->fill($request->all());
        $suspectCase->gestation = $request->gestation;
        $suspectCase->close_contact = $request->has('close_contact') ? 1 : 0;
        $suspectCase->discharge_test = $request->has('discharge_test') ? 1 : 0;

        $suspectCase->epidemiological_week = Carbon::createFromDate($suspectCase->sample_at->format('Y-m-d'))->add(1, 'days')->weekOfYear;

        /* Setar el validador */
        if ($log->old->pscr_sars_cov_2 == 'pending' and $suspectCase->pscr_sars_cov_2 != 'pending') {
            $suspectCase->validator_id = Auth::id();
        }

        $suspectCase->save();

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
            if ($log->old->pscr_sars_cov_2 == 'pending' and $suspectCase->pscr_sars_cov_2 == 'positive') {
                $emails  = explode(',', env('EMAILS_ALERT'));
                $emails_bcc  = explode(',', env('EMAILS_ALERT_BCC'));
                Mail::to($emails)->bcc($emails_bcc)->send(new NewPositive($suspectCase));
            }
        }

        $log->new = $suspectCase;
        $log->save();

        switch($suspectCase->laboratory_id) {
            case(1): $ruta = 'lab.suspect_cases.hetg'; break;
            case(2): $ruta = 'lab.suspect_cases.unap'; break;
            default: $ruta = 'lab.suspect_cases.index'; break;
        }

        return redirect()->route($ruta);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SuspectCase  $suspectCase
     * @return \Illuminate\Http\Response
     */
    public function destroy(SuspectCase $suspectCase)
    {
        $log = new Log();
        $log->old = clone $suspectCase;
        $log->new = $suspectCase->setAttribute('suspect_case', 'delete');
        $log->save();

        $suspectCase->delete();

        return redirect()->route('lab.suspect_cases.index');
    }

    public function report()
    {
        $patients = Patient::whereHas('suspectCases', function ($q) { $q->where('pscr_sars_cov_2','positive'); })->get();
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
        // echo '<pre>';
        // print_r($evo);
        // die();
        return view('lab.suspect_cases.report', compact('patients', 'cases', 'cases_other_region', 'evolucion'));
    }

    public function historical_report(Request $request)
    {
        if($request->has('date')){
          $date = $request->get('date');
        }else{
          $date = Carbon::now();
        }

        $reportBackup = ReportBackup::whereDate('created_at',$date)->get();
        if($reportBackup->count() <> 0){
          $html = json_decode($reportBackup->first()->data, true);

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

        // $cases_data = collect();
        // if($reportBackup->first() != null){
        //   $cases_data = collect(json_decode($reportBackup->first()->data, true));
        // }
        //
        // $cases = $cases_data->whereNotIn('patient.demographic.region',
        //                 [
        //                     'Arica y Parinacota',
        //                    'Antofagasta',
        //                    'Atacama',
        //                    'Coquimbo',
        //                    'Valparaíso',
        //                    'Región del Libertador Gral. Bernardo O’Higgins',
        //                    'Región del Maule',
        //                    'Región del Biobío',
        //                    'Región de la Araucanía',
        //                    'Región de Los Ríos',
        //                    'Región de Los Lagos',
        //                    'Región Aisén del Gral. Carlos Ibáñez del Campo',
        //                    'Región de Magallanes y de la Antártica Chilena',
        //                    'Región Metropolitana de Santiago',
        //                    'Región de Ñuble'])
        //                    ->whereNotIn('id',[1192,1008]);
        //                       // /->orWhereNull('patient.demographic.region')
        // //$cases_other_region = SuspectCase::All();
        // $cases_other_region = $cases_data->whereIn('patient.demographic.region',
        //                 [
        //                 'Arica y Parinacota',
        //                    'Antofagasta',
        //                    'Atacama',
        //                    'Coquimbo',
        //                    'Valparaíso',
        //                    'Región del Libertador Gral. Bernardo O’Higgins',
        //                    'Región del Maule',
        //                    'Región del Biobío',
        //                    'Región de la Araucanía',
        //                    'Región de Los Ríos',
        //                    'Región de Los Lagos',
        //                    'Región Aisén del Gral. Carlos Ibáñez del Campo',
        //                    'Región de Magallanes y de la Antártica Chilena',
        //                    'Región Metropolitana de Santiago',
        //                    'Región de Ñuble']);
        //
        // return view('lab.suspect_cases.reports.historical_report', compact('cases', 'cases_other_region', 'date'));
    }

    public function diary_lab_report(Request $request)
    {
        // if($request->has('date')){
        //   $date = $request->get('date');
        // }else{
        //   $date = Carbon::now();
        // }

        $total_muestras_diarias = DB::table('suspect_cases')
            ->select('sample_at', DB::raw('count(*) as total'))
            ->groupBy('sample_at')
            ->orderBy('sample_at')
            ->get();
        //dd($total_muestras_diarias->first()->sample_at);

        $total_muestras_x_lab = DB::table('suspect_cases')
                              ->select('pscr_sars_cov_2_at',
                                        DB::raw('(CASE
                                            			 WHEN external_laboratory IS NULL then (SELECT name FROM laboratories WHERE id = laboratory_id)
                                            			 ELSE external_laboratory
                                            		  END) AS laboratory'),
                                        DB::raw('count(*) as total')
                                      )
                              ->where('pscr_sars_cov_2', '<>', 'pending' )
                              ->where('pscr_sars_cov_2', '<>', 'rejected' )
                              ->groupBy('external_laboratory', 'laboratory_id', 'pscr_sars_cov_2_at')
                              ->orderBy('pscr_sars_cov_2_at')
                              ->get();

        //dd($total_muestras_x_lab);
        $total_muestras_x_lab_filas = array();
        $total_muestras_x_lab_columnas = array();

        foreach ($total_muestras_x_lab as $key => $muestra_x_lab) {
          $total_muestras_x_lab_columnas[$muestra_x_lab->laboratory] = 0;
          $total_muestras_x_lab_filas[$muestra_x_lab->pscr_sars_cov_2_at][$muestra_x_lab->laboratory]['cantidad'] = $muestra_x_lab->total;
        }

        foreach ($total_muestras_x_lab as $key => $muestra_x_lab) {
          $total_muestras_x_lab_columnas[$muestra_x_lab->laboratory] += $muestra_x_lab->total;
        }
        //dd($total_muestras_x_lab_columnas);

        return view('lab.suspect_cases.reports.diary_lab_report', compact('total_muestras_diarias','total_muestras_x_lab_columnas','total_muestras_x_lab_filas'));
    }


    public function case_tracing(Request $request)
    {
        $patients = Patient::whereHas('suspectCases', function ($q) { $q->where('pscr_sars_cov_2','positive'); })->get();
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

        $max_cases = 0;
        foreach ($patients as $patient) {
            if($max_cases < $patient->suspectCases->count())
                $max_cases = $patient->suspectCases->count();
        }

        return view('lab.suspect_cases.reports.case_tracing', compact('patients','max_cases'));
    }

    public function estadistico_diario_covid19(Request $request)
    {
        $yesterday = Carbon::now()->subDays(1)->format('Y-m-d 21:00');
        $now = Carbon::now()->format('Y-m-d 21:00');
        //dd($yesterday, $now);

        $array = array();
        $cases = SuspectCase::whereBetween('created_at',[$yesterday,$now])
                            ->where('external_laboratory',NULL)
                            ->get();
        //dd($cases);
        foreach ($cases as $key => $case) {
          $array[$case->laboratory->name]['muestras_en_espera'] = 0;
          $array[$case->laboratory->name]['muestras_recibidas'] = 0;
          $array[$case->laboratory->name]['muestras_procesadas'] = 0;
          $array[$case->laboratory->name]['muestras_positivas'] = 0;
          $array[$case->laboratory->name]['muestras_procesadas_acumulados'] = 0;
          $array[$case->laboratory->name]['muestras_procesadas_positivo'] = 0;
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
            $user->id = 18371078;
            $user->dv = 8;
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

    public function stat()
    {
        $cases = SuspectCase::All();

        return json_encode((object) [
            'total' => $cases->count(),
            'positives' => $cases = SuspectCase::where('pscr_sars_cov_2', 'positive')->count(),
            'pending' => $cases = SuspectCase::where('pscr_sars_cov_2', 'pending')->count(),
            'negatives' => $cases = SuspectCase::where('pscr_sars_cov_2', 'negative')->count()
        ]);
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
            if ($suspectCase->pscr_sars_cov_2 == 'positive' || $suspectCase->pscr_sars_cov_2 == 'pending') {
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
            if ($suspectCase->pscr_sars_cov_2 == 'pending') {
                $data[date("d", strtotime($suspectCase->sample_at)) . "/" . date("m", strtotime($suspectCase->sample_at)) . "/" . date("Y", strtotime($suspectCase->sample_at))]['pendientes'] += 1;
            }
            if ($suspectCase->pscr_sars_cov_2 == 'positive') {
                $data[date("d", strtotime($suspectCase->sample_at)) . "/" . date("m", strtotime($suspectCase->sample_at)) . "/" . date("Y", strtotime($suspectCase->sample_at))]['positivos'] += 1;
            }
        }

        return view('lab.suspect_cases.reports.case_chart', compact('suspectCases', 'data', 'from', 'to'));
    }

    public function file_report(Request $request)
    {
      $from = Carbon::now()->subDays(2);
      $to = Carbon::now();
      //dd($from, $to);
      $files = File::whereBetween('created_at', [$from, $to])
                   ->whereHas('suspectCase', function ($query) {
                        $query->where('pscr_sars_cov_2', 'like', 'positive');
                    })
                   ->orderBy('created_at','DESC')->get();

      $suspectCases = SuspectCase::whereBetween('created_at', [$from, $to])
                                 ->where('pscr_sars_cov_2', 'like', 'positive')
                                 ->where('laboratory_id', 2)
                                 ->get();

      return view('lab.suspect_cases.reports.file_report', compact('files','suspectCases'));
    }


    public function report_minsal($lab = null)
    {
        switch ($lab) {
            case 'hetg':
                $cod_lab = 1;
                break;
            case 'unap':
                $cod_lab = 2;
                break;
            default:
                $cod_lab = 1;
                break;
        }
        $today = date("Y-m-d");
        $cases = SuspectCase::where('laboratory_id',$cod_lab)->whereDate('pscr_sars_cov_2_at', '=', $today)->get()->sortByDesc('id');
        return view('lab.suspect_cases.reports.minsal', compact('cases', 'cod_lab'));
    }

    public function report_seremi($lab = null)
    {
        switch ($lab) {
            case 'hetg':
                $cod_lab = 1;
                break;
            case 'unap':
                $cod_lab = 2;
                break;
            default:
                $cod_lab = 1;
                break;
        }
        $cases = SuspectCase::where('laboratory_id',$cod_lab)->get()->sortDesc();
        return view('lab.suspect_cases.reports.seremi', compact('cases', 'cod_lab'));
    }

    public function hetg(Request $request)
    {
      if ($request->get('positivos') == "on") {
        $positivos = "positive";
      }else{$positivos = NULL;}

      if ($request->get('negativos') == "on") {
        $negativos = "negative";
      }else{$negativos = NULL;}

      if ($request->get('pendientes') == "on") {
        $pendientes = "pending";
      }else{$pendientes = NULL;}

      if ($request->get('rechazados') == "on") {
        $rechazados = "rejected";
      }else{$rechazados = NULL;}

      if ($request->get('indeterminados') == "on") {
        $indeterminados = "undetermined";
      }else{$indeterminados = NULL;}

      $text = $request->get('text');

      $suspectCases = SuspectCase::where('laboratory_id',1)
                                  ->whereHas('patient', function($q) use ($text){
                                          $q->Where('name', 'LIKE', '%'.$text.'%');
                                  })
                                  ->whereIn('pscr_sars_cov_2',[$positivos, $negativos, $pendientes, $rechazados, $indeterminados])
                                  ->paginate(50);//->appends(request()->query());

        return view('lab.suspect_cases.hetg', compact('suspectCases','request'));
    }

    public function unap(Request $request)
    {
        if ($request->get('positivos') == "on") {
          $positivos = "positive";
        }else{$positivos = NULL;}

        if ($request->get('negativos') == "on") {
          $negativos = "negative";
        }else{$negativos = NULL;}

        if ($request->get('pendientes') == "on") {
          $pendientes = "pending";
        }else{$pendientes = NULL;}

        if ($request->get('rechazados') == "on") {
          $rechazados = "rejected";
        }else{$rechazados = NULL;}

        if ($request->get('indeterminados') == "on") {
          $indeterminados = "undetermined";
        }else{$indeterminados = NULL;}

        $text = $request->get('text');

        $suspectCases = SuspectCase::where('laboratory_id',2)
                                    ->whereHas('patient', function($q) use ($text){
                                            $q->Where('name', 'LIKE', '%'.$text.'%');
                                    })
                                    ->whereIn('pscr_sars_cov_2',[$positivos, $negativos, $pendientes, $rechazados, $indeterminados])
                                    ->paginate(50);//->appends(request()->query());

        return view('lab.suspect_cases.unap', compact('suspectCases','request'));
        // return view('lab.suspect_cases.unap', ['suspectCases' => $suspectCases, 'request' => $request]);
    }

    public function exportExcel($cod_lab = mull){
        return Excel::download(new SuspectCasesExport($cod_lab), 'lista-casos.xlsx');
    }

    public function exportMinsalExcel($cod_lab = null)
    {
        switch ($cod_lab) {
            case '1':
                $nombre_lab = 'HETG';
                break;
            case '2':
                $nombre_lab = 'UNAP';
                break;
        }

        return Excel::download(new MinsalSuspectCasesExport($cod_lab, $nombre_lab), 'reporte-minsal.xlsx');
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
}
/*
    public function case_tracing(Request $request)
    {
        $patients = Patient::whereHas('suspectCases', function ($q) { $q->where('pscr_sars_cov_2','positive'); })->get();
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

        $max_cases = 0;
        foreach ($patients as $patient) {
            if($max_cases < $patient->suspectCases->count())
                $max_cases = $patient->suspectCases->count();
        }

        return view('lab.suspect_cases.reports.case_tracing', compact('patients','max_cases'));
    }
*/
