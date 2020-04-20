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

class SuspectCaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $suspectCases = SuspectCase::with('patient')->latest('id')->get();
        // echo '<pre>';
        // print_r($suspectCases->toArray());
        // die();
        return view('lab.suspect_cases.index', compact('suspectCases'));
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
        $cases = SuspectCase::All();
        $cases = $cases->whereNotIn('patient.demographic.region',
                        [
                            'Arica y Parinacota',
                           'Antofagasta',
                           'Atacama',
                           'Coquimbo',
                           'Valparaíso',
                           'Región del Libertador Gral. Bernardo O’Higgins',
                           'Región del Maule',
                           'Región del Ñuble',
                           'Región del Biobío',
                           'Región de la Araucanía',
                           'Región de Los Ríos',
                           'Región de Los Lagos',
                           'Región Aisén del Gral. Carlos Ibáñez del Campo',
                           'Región de Magallanes y de la Antártica Chilena',
                           'Región Metropolitana de Santiago']);
                              // /->orWhereNull('patient.demographic.region')
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
                           'Región del Ñuble',
                           'Región del Biobío',
                           'Región de la Araucanía',
                           'Región de Los Ríos',
                           'Región de Los Lagos',
                           'Región Aisén del Gral. Carlos Ibáñez del Campo',
                           'Región de Magallanes y de la Antártica Chilena',
                           'Región Metropolitana de Santiago']);

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
        return view('lab.suspect_cases.report', compact('cases', 'cases_other_region', 'evolucion'));
    }

    public function historical_report(Request $request)
    {
        if($request->has('date')){
          $date = $request->get('date');
        }else{
          $date = Carbon::now();
        }

        $reportBackup = ReportBackup::whereDate('created_at',$date)->get();
        $cases_data = collect();
        //dd($cases_data);
        //dd($date);
        if($reportBackup->first() != null){
          $cases_data = collect(json_decode($reportBackup->first()->data, true));
        }


        $cases = $cases_data->whereNotIn('patient.demographic.region',
                        [
                            'Arica y Parinacota',
                           'Antofagasta',
                           'Atacama',
                           'Coquimbo',
                           'Valparaíso',
                           'Región del Libertador Gral. Bernardo O’Higgins',
                           'Región del Maule',
                           'Región del Ñuble',
                           'Región del Biobío',
                           'Región de la Araucanía',
                           'Región de Los Ríos',
                           'Región de Los Lagos',
                           'Región Aisén del Gral. Carlos Ibáñez del Campo',
                           'Región de Magallanes y de la Antártica Chilena',
                           'Región Metropolitana de Santiago']);
                              // /->orWhereNull('patient.demographic.region')
        //$cases_other_region = SuspectCase::All();
        $cases_other_region = $cases_data->whereIn('patient.demographic.region',
                        [
                        'Arica y Parinacota',
                           'Antofagasta',
                           'Atacama',
                           'Coquimbo',
                           'Valparaíso',
                           'Región del Libertador Gral. Bernardo O’Higgins',
                           'Región del Maule',
                           'Región del Ñuble',
                           'Región del Biobío',
                           'Región de la Araucanía',
                           'Región de Los Ríos',
                           'Región de Los Lagos',
                           'Región Aisén del Gral. Carlos Ibáñez del Campo',
                           'Región de Magallanes y de la Antártica Chilena',
                           'Región Metropolitana de Santiago']);

        return view('lab.suspect_cases.historical_report', compact('cases', 'cases_other_region', 'date'));
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
        return view('lab.suspect_cases.reports.minsal', compact('cases'));
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
        return view('lab.suspect_cases.reports.seremi', compact('cases'));
    }

    public function hetg()
    {
        //Hospital Ernesto Torres Galdames laboratory_id = 1
        $suspectCases = SuspectCase::where('laboratory_id',1)->get()->sortByDesc('id');
        return view('lab.suspect_cases.hetg', compact('suspectCases'));
    }

    public function unap()
    {
        //laboratorio UNAP laboratory_id = 2
        $suspectCases = SuspectCase::where('laboratory_id',2)->get()->sortByDesc('id');
        return view('lab.suspect_cases.unap', compact('suspectCases'));
    }



}
