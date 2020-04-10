<?php

namespace App\Http\Controllers;

use App\SuspectCase;
use App\Patient;
use App\Log;
use App\File;
use App\User;
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
        if($request->id == null) {
            $patient = new Patient($request->All());
        }
        else {
            $patient = Patient::find($request->id);
            $patient->fill($request->all());
        }
        $patient->save();

        $suspectCase = new SuspectCase($request->All());
        $suspectCase->epidemiological_week = Carbon::createFromDate($suspectCase->sample_at->format('Y-m-d'))->add(1,'days')->weekOfYear;
        $patient->suspectCases()->save($suspectCase);

        //guarda archivos
        if($request->hasFile('forfile')){
            foreach($request->file('forfile') as $file) {
                $filename = $file->getClientOriginalName();
                $fileModel = New File;
                $fileModel->file = $file->store('files');
                $fileModel->name = $filename;
                $fileModel->suspect_case_id = $suspectCase->id;
                $fileModel->save();
            }
        }

        if($suspectCase->pscr_sars_cov_2 == 'positive') {
            $emails  = explode(',', env('EMAILS_ALERT'));
            $emails_bcc  = explode(',', env('EMAILS_ALERT_BCC'));
            Mail::to($emails)->bcc($emails_bcc)->send(new NewPositive($suspectCase));
        }

        $log = new Log();
        //$log->old = $suspectCase;
        $log->new = $suspectCase;
        $log->save();

        return redirect()->route('lab.suspect_cases.index');
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

        $suspectCase->epidemiological_week = Carbon::createFromDate($suspectCase->sample_at->format('Y-m-d'))->add(1,'days')->weekOfYear;

        $suspectCase->save();

        //guarda archivos
        if($request->hasFile('forfile')){
            foreach($request->file('forfile') as $file) {
                $filename = $file->getClientOriginalName();
                $fileModel = New File;
                $fileModel->file = $file->store('files');
                $fileModel->name = $filename;
                $fileModel->suspect_case_id = $suspectCase->id;
                $fileModel->save();
            }
        }

        if($log->old->pscr_sars_cov_2 == 'pending' AND $suspectCase->pscr_sars_cov_2 == 'positive') {
            $emails  = explode(',', env('EMAILS_ALERT'));
            $emails_bcc  = explode(',', env('EMAILS_ALERT_BCC'));
            Mail::to($emails)->bcc($emails_bcc)->send(new NewPositive($suspectCase));
        }

        $log->new = $suspectCase;
        $log->save();

        return redirect()->route('lab.suspect_cases.index');
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
        $log->new = $suspectCase->setAttribute('suspect_case','delete');
        $log->save();

        $suspectCase->delete();

        return redirect()->route('lab.suspect_cases.index');
    }

    public function report() {
        $cases = SuspectCase::All();
        $totales_dia = DB::table('suspect_cases')
                    ->select('sample_at', DB::raw('count(*) as total'))
                    ->where('pscr_sars_cov_2','positive')
                    ->groupBy('sample_at')
                    ->orderBy('sample_at')
                    ->get();

        $begin = new \DateTime($totales_dia->first()->sample_at);
        $end   = new \DateTime($totales_dia->last()->sample_at);

        for($i = $begin; $i <= $end; $i->modify('+1 day')){
            $evolucion[$i->format("Y-m-d")] = 0;

        }
        foreach($totales_dia as $dia) {
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
        return view('lab.suspect_cases.report', compact('cases','evolucion'));
    }

    public function download(File $file)
    {
        return Storage::response($file->file, mb_convert_encoding($file->name,'ASCII'));
    }

    public function login($access_token = null)
    {
        if($access_token) {
            return redirect()->route('lab.result')->with('access_token', $access_token);
        }
    }

    public function result()
    {
        if(env('APP_ENV') == 'production') {
            $access_token = session()->get( 'access_token' );
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
        }

        elseif(env('APP_ENV') == 'local') {
            $user = new User();
            $user->id = 18371078;
            $user->dv = 8;
            $user->name = "maria angela";
            $user->fathers_family = "family";
            $user->mothers_family = "mother";
            $user->email = "email@email.com";
        }

        Auth::login($user);
        $patient = Patient::where('run', $user->id)->first();
        return view('lab.result', compact('patient'));
    }

    public function print(){
        $pdf = \PDF::loadView('lab.results.result');
        return $pdf->stream();
    }

    public function stat() {
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
      if($from = $request->has('from')){
        $from = $request->get('from'). ' 00:00:00';
        $to = $request->get('to'). ' 23:59:59';
      }else{
        $from = Carbon::now()->firstOfMonth();
        $to = Carbon::now()->lastOfMonth();
      }

      $suspectCases = SuspectCase::whereBetween('sample_at',[$from,$to])->get();
      // ::latest('id')->get();
      $data = array();
      foreach ($suspectCases as $key => $suspectCase) {
        if ($suspectCase->pscr_sars_cov_2 == 'positive' || $suspectCase->pscr_sars_cov_2 == 'pending') {
          $data[date("d", strtotime($suspectCase->sample_at)) . "/" . date("m", strtotime($suspectCase->sample_at)) . "/" . date("Y", strtotime($suspectCase->sample_at))]['day'] = date("d", strtotime($suspectCase->sample_at));
          $data[date("d", strtotime($suspectCase->sample_at)) . "/" . date("m", strtotime($suspectCase->sample_at)) . "/" . date("Y", strtotime($suspectCase->sample_at))]['month'] = date("m", strtotime($suspectCase->sample_at))-1;
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

      return view('lab.suspect_cases.reports.case_chart', compact('suspectCases','data','from','to'));
    }
}
