<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use App\SuspectCase;
use App\Patient;
use App\Ventilator;
use App\File;
use App\SanitaryResidence\Residence;
use App\SanitaryResidence\Booking;
use Carbon\Carbon;
use App\Lab\Exam\Covid19;
use App\Laboratory;
use App\Region;
use App\WSMinsal;
use App\Commune;
use App\Country;
use Illuminate\View\View;

class SuspectCaseReportController extends Controller
{
    public function positives() {

        $patients = Patient::positivesList();

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
            if($patient->demographic AND $patient->demographic->commune) {
                $casos[$patient->demographic->commune->name][$patient->suspectCases->where('pscr_sars_cov_2','positive')->first()->sample_at->format('Y-m-d')] += 1;
            }

            if($patient->demographic != NULL && $patient->demographic->commune != NULL) {
                $casos[$patient->demographic->commune->name][$patient->suspectCases->where('pscr_sars_cov_2','positive')->first()->sample_at->format('Y-m-d')] += 1;
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
        return view('lab.suspect_cases.reports.positives', compact('patients','evolucion','ventilator','exams','communes'));

    }



    /*****************************************************/
    /*                 SEGUIMIENTO CASOS                 */
    /*****************************************************/
    public function case_tracing(Request $request)
    {
//        $patients = Patient::
//            whereHas('suspectCases', function ($q) {
//              $q->where('pscr_sars_cov_2','positive');
//            })
//            ->with('inmunoTests')
//            ->get();

//        $region_not = array_diff( [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16], [env('REGION')] );
//        $patients = $patients->whereNotIn('demographic.region_id', $region_not);

        $env_communes = array_map('trim', explode(",", env('COMUNAS')));

        $patients = Patient::whereHas('suspectCases', function ($q) {
            $q->where('pscr_sars_cov_2', 'positive');
        })->whereHas('demographic', function ($q) use ($env_communes) {
            $q->whereIn('commune_id', $env_communes);
        })
            ->with('inmunoTests')
            ->get();

        $patientsNoDemographic = Patient::whereHas('suspectCases', function ($q) {
            $q->where('pscr_sars_cov_2', 'positive');
        })->doesntHave('demographic')
            ->with('inmunoTests')
            ->get();

//        dd($patientsNoDemographic);

        $max_cases = 0;
        $max_cases_inmuno = 0;
        foreach ($patients as $patient) {
            if($max_cases < $patient->suspectCases->count()){
                $max_cases = $patient->suspectCases->count();
            }
            if($max_cases_inmuno < $patient->inmunoTests->count()){
                $max_cases_inmuno = $patient->inmunoTests->count();
            }
        }

        $max_cases_no_demographic = 0;
        $max_cases_inmuno_no_demographic = 0;
        foreach ($patientsNoDemographic as $patient) {
            if($max_cases_no_demographic < $patient->suspectCases->count()){
                $max_cases_no_demographic = $patient->suspectCases->count();
            }
            if($max_cases_inmuno_no_demographic < $patient->inmunoTests->count()){
                $max_cases_inmuno_no_demographic = $patient->inmunoTests->count();
            }
        }

        return view('lab.suspect_cases.reports.case_tracing', compact('patients','patientsNoDemographic', 'max_cases', 'max_cases_inmuno', 'max_cases_no_demographic', 'max_cases_inmuno_no_demographic'));
    }

    public function case_tracing_excel(Request $request)
    {
        $patients = Patient::
            whereHas('suspectCases', function ($q) {
              $q->where('pscr_sars_cov_2','positive')
              ->Where('patient_id', 12604);
            })
            ->with('contactPatient')
            ->get();
        $region_not = array_diff( [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16], [env('REGION')] );
        $patients = $patients->whereNotIn('demographic.region_id', $region_not);

        // dd($patients);

        // $max_cases = 0;
        // $max_cases_inmuno = 0;
        // foreach ($patients as $patient) {
        //     if($max_cases < $patient->suspectCases->count()){
        //         $max_cases = $patient->suspectCases->count();
        //     }
        //     if($max_cases_inmuno < $patient->inmunoTests->count()){
        //         $max_cases_inmuno = $patient->inmunoTests->count();
        //     }
        //
        // }

        return view('lab.suspect_cases.reports.case_tracing_excel', compact('patients'));
    }

    public function case_tracing_export()
    {
        $env_communes = array_map('trim', explode(",", env('COMUNAS')));

        $patients = Patient::whereHas('suspectCases', function ($q) {
            $q->where('pscr_sars_cov_2', 'positive');
        })->whereHas('demographic', function ($q) use ($env_communes) {
            $q->whereIn('commune_id', $env_communes);
        })
            ->with('inmunoTests')
            ->get();

        $max_cases = 0;
        $max_cases_inmuno = 0;
        foreach ($patients as $patient) {
            if($max_cases < $patient->suspectCases->count()){
                $max_cases = $patient->suspectCases->count();
            }
            if($max_cases_inmuno < $patient->inmunoTests->count()){
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
            'Estado',
        );

        $columnas_covid = array();
        for($i=1; $i <= $max_cases; $i++){
            $columnas_covid[] = 'PCR '.$i;
            $columnas_covid[] = 'Fecha Muestra '.$i;
            $columnas_covid[] = 'Fecha Resultado '.$i;
            $columnas_covid[] = 'Resultado '.$i;
            $columnas_covid[] = 'S '.$i;
        }

        $columnas_inmuno = array();
        for($i=1; $i <= $max_cases_inmuno; $i++){
            $columnas_inmuno[] = 'IgG/IgM '.$i;
            $columnas_inmuno[] = 'Fecha Test'.$i;
            $columnas_inmuno[] = 'IgG '.$i;
            $columnas_inmuno[] = 'IgM '.$i;
            $columnas_inmuno[] = 'Control '.$i;
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

        $columnas= array_merge($columnas_paciente, $columnas_covid, $columnas_inmuno, $columnas_cases);

        foreach ($patients as $key => $patient) {
            $casos[$key][] = $patient->fullName;
            $casos[$key][] = $patient->identifier;
            $casos[$key][] = $patient->age;
            $casos[$key][] = $patient->genderEsp;
            $casos[$key][] = ($patient->demographic AND $patient->demographic->commune)?$patient->demographic->commune->name:'';
            $casos[$key][] = ($patient->demographic)?$patient->demographic->nationality:'';
            $casos[$key][] = $patient->status;
            foreach($patient->suspectCases as $suspectCase) {
                $casos[$key][] = $suspectCase->id;
                $casos[$key][] = $suspectCase->sample_at->format('Y-m-d');
                $casos[$key][] = ($suspectCase->pscr_sars_cov_2_at) ? $suspectCase->pscr_sars_cov_2_at->format('Y-m-d') : '';
                $casos[$key][] = $suspectCase->covid19;
                $casos[$key][] = $suspectCase->symptoms;
            }
            for($i = $patient->suspectCases->count(); $i < $max_cases; $i++) {
                $casos[$key][] = '';
                $casos[$key][] = '';
                $casos[$key][] = '';
                $casos[$key][] = '';
                $casos[$key][] = '';
            }
            foreach ($patient->inmunoTests as $inmunoTest) {
                $casos[$key][] = $inmunoTest->id;
                $casos[$key][] = ($inmunoTest->register_at)?$inmunoTest->register_at->format('Y-m-d H:i:s'):'';
                $casos[$key][] = strtoupper(($inmunoTest->IgValue) ? $inmunoTest->IgValue : '');
                $casos[$key][] = strtoupper(($inmunoTest->ImValue) ? $inmunoTest->ImValue : '');
                $casos[$key][] = strtoupper($inmunoTest->ControlValue);
            }
            for($i = $patient->inmunoTests->count(); $i < $max_cases_inmuno; $i++) {
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
            $casos[$key][] = ($patient->suspectCases->first()->close_contact == 1) ? 'Sí':'';
            $casos[$key][] = ($patient->suspectCases->first()->sent_isp_at) ? $patient->suspectCases->first()->sent_isp_at->format('Y-m-d') : '';
            $casos[$key][] = $patient->suspectCases->first()->procesingLab ;
            $casos[$key][] = ($patient->suspectCases->first()->notification_at) ? $patient->suspectCases->first()->notification_at->format('Y-m-d') : '';
            $casos[$key][] = $patient->suspectCases->first()->notification_mechanism;
            $casos[$key][] = ($patient->suspectCases->first()->discharged_at) ? $patient->suspectCases->first()->discharged_at->format('Y-m-d') : '';
            $casos[$key][] = $patient->suspectCases->first()->observation;
        }

        $callback = function() use ($casos, $columnas)
        {

            $file = fopen('php://output', 'w');
            fputs($file, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));

            fputcsv($file, $columnas,';');

            foreach ($casos as $fila) {
                fputcsv($file, $fila,';');
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

        if($from = $request->has('from')){
            $from = $request->get('from'). ' 21:00:00';
            $to = $request->get('to'). ' 20:59:59';
        }else{
            $from = date("Y-m-d 21:00:00", time() - 60 * 60 * 24);
            $to = date("Y-m-d 20:59:59");
        }

        $externos = Covid19::whereBetween('result_at', [$from, $to])->get();

        $cases = SuspectCase::where('laboratory_id',$laboratory->id)
                ->whereBetween('pscr_sars_cov_2_at', [$from, $to])
                ->whereNull('external_laboratory')
                ->get()
                ->sortByDesc('pscr_sars_cov_2_at');
        return view('lab.suspect_cases.reports.minsal', compact('cases', 'laboratory', 'externos', 'from', 'to', 'request'));
    }

    /*****************************************************/
    /*                  REPORTE MINSAL WS                */
    /*****************************************************/
    public function report_minsal_ws(Laboratory $laboratory)
    {
        $from = '2020-06-01 00:00';//date("Y-m-d 21:00:00", time() - 60 * 60 * 24);
        $to = date("Y-m-d 20:59:59");

        // $externos = Covid19::whereBetween('result_at', [$from, $to])->get();

        $cases = SuspectCase::where('laboratory_id',$laboratory->id)
                ->whereBetween('pscr_sars_cov_2_at', [$from, $to])
                ->whereNull('external_laboratory')
                ->whereNULL('minsal_ws_id')
                ->get()
                ->sortByDesc('pscr_sars_cov_2_at');

        // //obtiene datos que faltan
        // foreach ($cases as $key => $case) {
        //
        //     $genero = strtoupper($case->gender[0]);
        //     //$commune_code_deis = Commune::find($case->patient->demographic->commune_id)->code_deis;
        //     $paciente_ext_paisorigen = '';
        //     if($case->patient->run == "") {
        //         $paciente_tipodoc = "PASAPORTE";
        //         $country = Country::where('name',$case->patient->demographic->nationality)->get();
        //         $paciente_ext_paisorigen = $country->first()->id_minsal;
        //     }
        //     else {
        //         $paciente_tipodoc = "RUN";
        //     }
        //     $case->genero = $genero;
        //     $case->commune_code_deis = $case->patient->commune;
        //     $case->paciente_tipodoc = $paciente_tipodoc;
        //     $case->paciente_ext_paisorigen = $paciente_ext_paisorigen;
        // }

        // dd($cases->first());
        return view('lab.suspect_cases.reports.minsal_ws', compact('cases', 'laboratory'));//,'externos'));
    }


    /*****************************************************/
    /*                    WS - Minsal                    */
    /*****************************************************/
    public function ws_minsal(Laboratory $laboratory)
    {
        $from = '2020-06-01 00:00';//date("Y-m-d 21:00:00", time() - 60 * 60 * 24);
        $to = date("Y-m-d 20:59:59");

        $externos = Covid19::whereBetween('result_at', [$from, $to])->get();

        $cases = SuspectCase::where('laboratory_id',$laboratory->id)
                ->whereBetween('pscr_sars_cov_2_at', [$from, $to])
                ->whereNull('external_laboratory')
                ->whereNULL('minsal_ws_id')
                ->get()
                ->sortByDesc('pscr_sars_cov_2_at');

                // $cases = SuspectCase::where('id',13784)->get();
                // dd($cases);

        // dd($cases);
        foreach ($cases as $key => $case) {
            if ($case->run_medic != 0) {
                if ($case->patient->demographic && $case->files) {
                    $response = WSMinsal::crea_muestra($case);
                    if ($response['status'] == 0) {
                        session()->flash('info', 'Error al subir muestra ' . $case->id . ' a MINSAL. ' . $response['msg']);
                        // return view('lab.suspect_cases.reports.minsal_ws', compact('cases', 'laboratory','externos'));
                    }else{
                        $response = WSMinsal::recepciona_muestra($case);
                        if ($response['status'] == 0) {
                            session()->flash('info', 'Error al recepcionar muestra ' . $case->id . ' en MINSAL. ' . $response['msg']);
                            // return view('lab.suspect_cases.reports.minsal_ws', compact('cases', 'laboratory','externos'));
                        }else{
                            $response = WSMinsal::resultado_muestra($case);
                            if ($response['status'] == 0) {
                                session()->flash('info', 'Error al subir resultado de muestra ' . $case->id . ' en MINSAL. ' . $response['msg']);
                                // return view('lab.suspect_cases.reports.minsal_ws', compact('cases', 'laboratory','externos'));
                            }
                        }
                    }
                }
            }else{
                session()->flash('info', 'No se detectó run de médico registrado en muestra:  ' . $case->id);
            }
        }

        session()->flash('success', 'Se ha subido la información a sistema MINSAL.');
        return redirect()->back();
    }

    /*****************************************************/
    /*                  REPORTE SEREMI                   */
    /*****************************************************/
    public function report_seremi(Laboratory $laboratory)
    {

        $cases = SuspectCase::where('laboratory_id',$laboratory->id)->get()->sortDesc();
        return view('lab.suspect_cases.reports.seremi', compact('cases', 'laboratory'));
    }



    /*****************************************************/
    /*                REPORTE GESTANTES                  */
    /*****************************************************/
    public function gestants() {
        $patients = Patient::whereHas('suspectCases', function ($q) {
            $q->where('gestation',1);
        })->with('suspectCases')->get();

        return view('lab.suspect_cases.reports.gestants', compact('patients'));
    }

    /*****************************************************/
    /*              CONTADOR DE POSITIVOS                */
    /*****************************************************/
    public function countPositives(Request $request)
    {
        $patients = Patient::positivesList();

        if($request->input('residence')) {
            $bookings = Booking::where('status','Residencia Sanitaria')
                        ->whereHas('patient', function ($q) {
                            $q->where('status','Residencia Sanitaria');
                        })->get();
            $booking_ct = $bookings->where('room.residence_id',$request->input('residence'))->count();
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



    public function exams_with_result(Request $request)
    {
        // $from =Carbon::now()->subDays(2);
        //
        // $patients = Patient::whereHas('suspectCases', function ($q) {
        //     $q->where('pscr_sars_cov_2','positive');
        // })->with('suspectCases')->with('demographic')->get();


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

        return view('lab.suspect_cases.reports.exams_with_result', compact('files','suspectCases'));
    }


    public function apuntes() {
        $patients = Patient::all();
        foreach($patients as $patient) {
            foreach($patient->suspectCases as $case) {
                if($case->status) {
                    $patient->status = $case->status;
                    $patient->save();
                }
            }
        }
    }

    /**
     * Obtiene suspectsCases positivos con datos de demographics por
     * rango de fecha
     * @param Request $request
     * @return Application|Factory|View
     */
    public function positivesByDateRange(Request $request){

        if($from = $request->has('from')){
            $from = $request->get('from'). ' 00:00:00';
            $to = $request->get('to'). ' 23:59:59';
        }else{
            $from = Carbon::yesterday();
            $to = Carbon::now();
        }

        $suspectCases = SuspectCase::whereBetween('pscr_sars_cov_2_at', [$from, $to])
            ->where('pscr_sars_cov_2', 'positive')->orderBy('pscr_sars_cov_2_at')->get();

        return view('lab.suspect_cases.reports.positivesByDateRange', compact('suspectCases', 'from', 'to'));
    }


    /*****************************************************/
    /*            REPORTE SISTEMAS EXPERTOS              */
    /*****************************************************/
    public function reporteExpertos()
    {
        $from = Carbon::yesterday();
        $to = Carbon::now();

        $patients = Patient::whereHas('suspectCases', function ($q) use($from,$to)  {
            $q->whereBetween('pscr_sars_cov_2_at',[$from,$to]);
        })->with('suspectCases')->get();

        //dd($patients);
        return response()->json($patients);
    }

}
