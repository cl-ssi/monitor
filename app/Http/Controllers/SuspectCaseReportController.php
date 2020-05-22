<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SuspectCase;
use App\Patient;
use App\Ventilator;
use App\File;
use App\SanitaryResidence\Residence;
use App\SanitaryResidence\Booking;
use Carbon\Carbon;
use App\Lab\Exam\Covid19;

class SuspectCaseReportController extends Controller
{
    public function positives() {
        $bookings = Booking::where('status','Residencia Sanitaria')
                    ->whereHas('patient', function ($q) {
                        $q->where('status','Residencia Sanitaria');
                    })->get();
        $residences = Residence::all();


        $patients = Patient::whereHas('suspectCases', function ($q) {
            $q->where('pscr_sars_cov_2','positive');
        })->with('suspectCases')->with('demographic')->get();

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

        /* Calculo de gráfico de evolución */
        $begin = SuspectCase::where('pscr_sars_cov_2','positive')->orderBy('sample_at')->first()->sample_at;
        $end   = SuspectCase::where('pscr_sars_cov_2','positive')->orderByDesc('sample_at')->first()->sample_at;

        for ($i = $begin; $i <= $end; $i->modify('+1 day')) {
            $casos['Region'][$i->format("Y-m-d")] = 0;
            $casos['Alto Hospicio'][$i->format("Y-m-d")] = 0;
            $casos['Iquique'][$i->format("Y-m-d")] = 0;
            $casos['Pica'][$i->format("Y-m-d")] = 0;
            $casos['Pozo Almonte'][$i->format("Y-m-d")] = 0;
            $casos['Huara'][$i->format("Y-m-d")] = 0;
            $casos['Camiña'][$i->format("Y-m-d")] = 0;
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

        //echo '<pre>'; print_r($patients->where('status','Hospitalizado UCI')->count()); die();
        //echo '<pre>'; print_r($evolucion); die();
        return view('lab.suspect_cases.reports.positives', compact('patients','evolucion','ventilator','residences','bookings','exams'));

    }


    /*****************************************************/
    /*                 SEGUIMIENTO CASOS                 */
    /*****************************************************/
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



    /*****************************************************/
    /*                  REPORTE MINSAL                   */
    /*****************************************************/
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
        $from = date("Y-m-d 21:00:00", time() - 60 * 60 * 24);
        $to = date("Y-m-d 20:59:59");

        $externos = Covid19::whereBetween('result_at', [$from, $to])->get();
        $cases = SuspectCase::where('laboratory_id',$cod_lab)
                ->whereBetween('pscr_sars_cov_2_at', [$from, $to])
                ->whereNull('external_laboratory')
                ->get()
                ->sortByDesc('pscr_sars_cov_2_at');
        return view('lab.suspect_cases.reports.minsal', compact('cases', 'cod_lab','externos'));
    }



    /*****************************************************/
    /*                  REPORTE SEREMI                   */
    /*****************************************************/
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


    public function gestants() {
        $patients = Patient::whereHas('suspectCases', function ($q) {
            $q->where('gestation',1);
        })->with('suspectCases')->get();

        return view('lab.suspect_cases.reports.gestants', compact('patients'));
    }

    public function countPositives(Request $request)
    {
        $patients = Patient::whereHas('suspectCases', function ($q) {
            $q->where('pscr_sars_cov_2','positive');
        })->with('suspectCases')->with('demographic')->get();

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


}
