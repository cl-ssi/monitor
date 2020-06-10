<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Response;
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
        return view('lab.suspect_cases.reports.positives', compact('patients','evolucion','ventilator','exams','communes'));

    }



    /*****************************************************/
    /*                 SEGUIMIENTO CASOS                 */
    /*****************************************************/
    public function case_tracing(Request $request)
    {
        $patients = Patient::
            whereHas('suspectCases', function ($q) {
              $q->where('pscr_sars_cov_2','positive');
            })
            ->with('inmunoTests')
            ->get();
        $region_not = array_diff( [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16], [env('REGION')] );
        $patients = $patients->whereNotIn('demographic.region_id', $region_not);

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

        return view('lab.suspect_cases.reports.case_tracing', compact('patients','max_cases', 'max_cases_inmuno'));
    }

    public function case_tracing_export()
    {
        die('hola');
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=seguimiento.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $filas = Patient::latest()
            ->whereHas('suspectCases', function ($q) {
                $q->where('pscr_sars_cov_2','positive');
            })
            ->with('suspectCases')
            ->with('inmunoTests')
            ->get();

        $region_not = array_diff( [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16], [env('REGION')] );
        $filas = $filas->whereNotIn('demographic.region_id', $region_not);

        $max_cases = 0;
        $max_cases_inmuno = 0;
        foreach ($filas as $patient) {
            if($max_cases < $patient->suspectCases->count()){
                $max_cases = $patient->suspectCases->count();
            }
            if($max_cases_inmuno < $patient->inmunoTests->count()){
                $max_cases_inmuno = $patient->inmunoTests->count();
            }
        }

        // CONTRUCCION DEL HEADER DEL ARCHIVO //

        $columnas_paciente = array(
            'Paciente',
            'Run',
            'Edad',
            'Sexo',
            'Comuna',
            'Nacionalidad',
            'Estado',
        );

        $columnas_covid = array();

        $count_columns_covid = 1;
        for($i=1; $i <= $max_cases; $i++){
            $columnas_covid[$count_columns_covid] = 'ID_'.$i;
            $count_columns_covid++;
            $columnas_covid[$count_columns_covid] = 'Fecha Muestra_'.$i;
            $count_columns_covid++;
            $columnas_covid[$count_columns_covid] = 'Fecha Resultado_'.$i;
            $count_columns_covid++;
            $columnas_covid[$count_columns_covid] = 'Covid_'.$i;
            $count_columns_covid++;
            $columnas_covid[$count_columns_covid] = 'S_'.$i;
            $count_columns_covid++;
        }

        $columnas_inmuno = array();

        $count_columns_inmuno = 1;
        for($i=1; $i <= $max_cases_inmuno; $i++){
            $columnas_inmuno[$count_columns_inmuno] = 'ID_I'.$i;
            $count_columns_inmuno++;
            $columnas_inmuno[$count_columns_inmuno] = 'Fecha Examen_'.$i;
            $count_columns_inmuno++;
            $columnas_inmuno[$count_columns_inmuno] = 'IgG_'.$i;
            $count_columns_inmuno++;
            $columnas_inmuno[$count_columns_inmuno] = 'IgM_'.$i;
            $count_columns_inmuno++;
            $columnas_inmuno[$count_columns_inmuno] = 'Control_'.$i;
            $count_columns_inmuno++;
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

        dd($columnas);

        // ------------------------------------------------------------------ //

        // $rows = array();
        //
        // $i=0;
        // foreach ($filas as $key_filas => $fila) {
        //     $rows[$key_filas][$i++] = $fila->fullName;
        //     $rows[$key_filas][$i++] = $fila->identifier;
        //     $rows[$key_filas][$i++] = $fila->age;
        //     $rows[$key_filas][$i++] = $fila->sexEsp;
        //
        //     if($fila->demographic){
        //       $rows[$key_filas][$i++] = $fila->demographic->commune;
        //       $rows[$key_filas][$i++] = $fila->demographic->nationality;
        //     }
        //     else{
        //       $rows[$key_filas][$i++] = '';
        //       $rows[$key_filas][$i++] = '';
        //     }
        //
        //     foreach ($fila->SuspectCases as $suspectCase) {
        //       if($fila->SuspectCases){
        //           $rows[$key_filas][$i++] = $suspectCase->id;
        //           $rows[$key_filas][$i++] = $suspectCase->sample_at->format('Y-m-d');
        //           if($suspectCase->pscr_sars_cov_2_at){
        //               $rows[$key_filas][$i++] = $suspectCase->pscr_sars_cov_2_at->format('Y-m-d');
        //           }
        //           else{
        //               $rows[$key_filas][$i++] = '';
        //           }
        //
        //           $rows[$key_filas][$i++] = $suspectCase->covid19;
        //           $rows[$key_filas][$i++] = $suspectCase->symptoms;
        //       }
        //       else{
        //           $rows[$key_filas][$i++] = '';
        //           $rows[$key_filas][$i++] = '';
        //           $rows[$key_filas][$i++] = '';
        //           $rows[$key_filas][$i++] = '';
        //           $rows[$key_filas][$i++] = '';
        //       }
        //     }
        //
        //     for($j=$fila->suspectCases->count(); $j < $max_cases; $j++){
        //         $rows[$key_filas][$i++] = '';
        //         $rows[$key_filas][$i++] = '';
        //         $rows[$key_filas][$i++] = '';
        //         $rows[$key_filas][$i++] = '';
        //         $rows[$key_filas][$i++] = '';
        //     }
        //
        //     foreach ($fila->inmunoTests as $inmunoTest) {
        //       if($fila->inmunoTests){
        //           $rows[$key_filas][$i++] = $inmunoTest->id;
        //           $rows[$key_filas][$i++] = $inmunoTest->register_at->format('Y-m-d H:i:s');
        //           $rows[$key_filas][$i++] = $inmunoTest->IgValue;
        //           $rows[$key_filas][$i++] = $inmunoTest->ImValue;
        //           $rows[$key_filas][$i++] = $inmunoTest->ControlValue;
        //       }
        //       else{
        //           $rows[$key_filas][$i++] = '';
        //           $rows[$key_filas][$i++] = '';
        //           $rows[$key_filas][$i++] = '';
        //           $rows[$key_filas][$i++] = '';
        //           $rows[$key_filas][$i++] = '';
        //       }
        //     }
        //
        //     for($j=$fila->inmunoTests->count(); $j < $max_cases_inmuno; $j++){
        //         $rows[$key_filas][$i++] = '';
        //         $rows[$key_filas][$i++] = '';
        //         $rows[$key_filas][$i++] = '';
        //         $rows[$key_filas][$i++] = '';
        //         $rows[$key_filas][$i++] = '';
        //     }
        //
        //     if($fila->result_ifd_at)
        //         $rows[$key_filas][$i++] = $fila->suspectCases->result_ifd_at->format('Y-m-d');
        //     else {
        //         $rows[$key_filas][$i++] = '';
        //     }
        //
        //     $rows[$key_filas][$i++] = $fila->suspectCases->first()->result_ifd;
        //
        //     if($fila->suspectCases->first()->establishment)
        //         $rows[$key_filas][$i++] = $fila->suspectCases->first()->establishment->alias.' - '.$fila->suspectCases->first()->origin;
        //     else {
        //         $rows[$key_filas][$i++] = '';
        //     }
        //
        //     $rows[$key_filas][$i++] = $fila->suspectCases->first()->epidemiological_week;
        //     $rows[$key_filas][$i++] = $fila->suspectCases->first()->epivigila;
        //     $rows[$key_filas][$i++] = $fila->suspectCases->first()->paho_flu;
        //
        //     if($fila->suspectCases->first()->gestation == 1){
        //         $rows[$key_filas][$i++] = 'Si';
        //     }
        //     else {
        //         $rows[$key_filas][$i++] = '';
        //     }
        //
        //     if($fila->suspectCases->first()->close_contact == 1){
        //         $rows[$key_filas][$i++] = 'Si';
        //     }
        //     else {
        //         $rows[$key_filas][$i++] = '';
        //     }
        //
        //     if($fila->suspectCases->first()->sent_isp_at)
        //         $rows[$key_filas][$i++] = $fila->suspectCases->first()->sent_isp_at->format('Y-m-d');
        //     else {
        //         $rows[$key_filas][$i++] = '';
        //     }
        //
        //     $rows[$key_filas][$i++] = $fila->suspectCases->first()->procesingLab;
        //
        //     if($fila->suspectCases->first()->notification_at)
        //         $rows[$key_filas][$i++] = $fila->suspectCases->first()->notification_at->format('Y-m-d');
        //     else {
        //         $rows[$key_filas][$i++] = '';
        //     }
        //
        //     $rows[$key_filas][$i++] = $fila->suspectCases->first()->notification_mechanism;
        //
        //     if($fila->suspectCases->first()->discharged_at)
        //         $rows[$key_filas][$i++] = $fila->suspectCases->first()->discharged_at->format('Y-m-d');
        //     else {
        //         $rows[$key_filas][$i++] = '';
        //     }
        //
        //     $rows[$key_filas][$i++] = $fila->suspectCases->first()->observation;
        //
        //     $i++;
        // }

        // dd($rows[0]);

        // $callback = function() use ($filas, $columnas)
        // {
        //     $file = fopen('php://output', 'w');
        //     fputs($file, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
        //     fputcsv($file, $columnas,';');
        //
        //     foreach($filas as $key => $fila) {
        //         // dd($row);
        //         fputcsv($file, ,';');
        //     }
        //
        //     // foreach($filas->SuspectCases as $key => $suspectCase) {
        //     //     // dd($row);
        //     //     fputcsv($file, array(
        //     //       $suspectCase->id,
        //     //     ),';');
        //     // }
        //
        //     fclose($file);
        //
        // };
        // return response()->stream($callback, 200, $headers);
        // return Response::stream($callback, 200, $headers);
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
        return view('lab.suspect_cases.reports.minsal', compact('cases', 'laboratory', 'externos', 'from', 'to'));
    }

    /*****************************************************/
    /*                  REPORTE MINSAL WS                */
    /*****************************************************/
    public function report_minsal_ws(Laboratory $laboratory)
    {
        $from = date("Y-m-d 21:00:00", time() - 60 * 60 * 24);
        $to = date("Y-m-d 20:59:59");

        // $externos = Covid19::whereBetween('result_at', [$from, $to])->get();

        $cases = SuspectCase::where('laboratory_id',$laboratory->id)
                ->whereBetween('pscr_sars_cov_2_at', [$from, $to])
                ->whereNull('external_laboratory')
                ->whereNULL('minsal_ws_id')
                ->get()
                ->sortByDesc('pscr_sars_cov_2_at');

        //obtiene datos que faltan
        foreach ($cases as $key => $case) {

            $genero = strtoupper($case->gender[0]);
            $commune_code_deis = Commune::find($case->patient->demographic->commune_id)->code_deis;
            $paciente_ext_paisorigen = '';
            if($case->patient->run == "") {
                $paciente_tipodoc = "PASAPORTE";
                $country = Country::where('name',$case->patient->demographic->nationality)->get();
                $paciente_ext_paisorigen = $country->first()->id_minsal;
            }
            else {
                $paciente_tipodoc = "RUN";
            }
            $case->genero = $genero;
            $case->commune_code_deis = $commune_code_deis;
            $case->paciente_tipodoc = $paciente_tipodoc;
            $case->paciente_ext_paisorigen = $paciente_ext_paisorigen;
        }

        // dd($cases->first());
        return view('lab.suspect_cases.reports.minsal_ws', compact('cases', 'laboratory'));//,'externos'));
    }


    /*****************************************************/
    /*                    WS - Minsal                    */
    /*****************************************************/
    public function ws_minsal(Laboratory $laboratory)
    {
        $from = date("Y-m-d 21:00:00", time() - 60 * 60 * 24);
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


    public function gestants() {
        $patients = Patient::whereHas('suspectCases', function ($q) {
            $q->where('gestation',1);
        })->with('suspectCases')->get();

        return view('lab.suspect_cases.reports.gestants', compact('patients'));
    }

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

}
