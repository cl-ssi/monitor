<?php

namespace App\Http\Controllers;

use App\Tracing\Tracing;
use Illuminate\Http\Request;
use App\Patient;
use Carbon\Carbon;
use App\EstablishmentUser;
use App\Commune;
use App\Establishment;
use App\SuspectCase;


class TracingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function withoutTracing(Request $request)
    {
        //
        $patients = Patient::search($request->input('search'))->doesntHave('tracing')->whereHas('suspectCases', function ($q) {
            $q->where('pscr_sars_cov_2','positive')
            ->where('pscr_sars_cov_2_at', '>=', '2020-06-23');
        })->paginate(200);
        return view('patients.tracing.withouttracing', compact('patients','request'));

    }


    public function indexByCommune()
    {
        if(auth()->user()->establishments->count() == 0){
            session()->flash('info', 'Usuario no tiene establecimientos asociados.');
            return  redirect()->back();
        }

        $patients = Patient::whereHas('demographic', function($q) {
                $q->whereIn('commune_id', auth()->user()->communes());
            })
            ->whereHas('tracing', function($q) {
                    $q->where('status',1)
                      ->orderBy('next_control_at');
                })
            ->where(function ($q) {
                $q->whereNotIn('status',[
                    'Fallecido',
                    'Alta',
                    'Residencia Sanitaria',
                    'Hospitalizado Básico',
                    'Hospitalizado Medio',
                    'Hospitalizado UCI',
                    'Hospitalizado UTI',
                    'Hospitalizado UCI (Ventilador)'])
                  ->orWhereNull('status');
             })
            ->with('tracing')
            ->with('demographic')
            ->get()
            ->sortBy(function($q){
                return $q->tracing->next_control_at;
            })
            ->all();
        //dd($patients);

        return view('patients.tracing.index', compact('patients'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexByEstablishment()
    {
        $patients = Patient::whereHas('tracing', function($q) {
                $q->whereIn('establishment_id', auth()->user()->establishments->pluck('id')->toArray())
                  ->where('status',1)
                  ->orderBy('next_control_at');
            })
            ->where(function ($q) {
                $q->whereNotIn('status',[
                    'Fallecido',
                    'Alta',
                    'Residencia Sanitaria',
                    'Hospitalizado Básico',
                    'Hospitalizado Medio',
                    'Hospitalizado UCI',
                    'Hospitalizado UTI',
                    'Hospitalizado UCI (Ventilador)'
                ])
                ->orWhereNull('status');
             })
            ->with('tracing')
            ->with('demographic')
            ->get()
            ->sortBy(function($q){
                return $q->tracing->next_control_at;
                })
            ->all();

        return view('patients.tracing.index', compact('patients'));
    }



    public function mapByEstablishment()
    {
        $patients = Patient::whereHas('tracing', function($q) {
                $q->whereIn('establishment_id', auth()->user()->establishments->pluck('id')->toArray())
                  ->where('status',1)
                  ->orderBy('next_control_at');
            })
            ->where(function ($q) {
                $q->whereNotIn('status',[
                    'Fallecido',
                    'Alta',
                    'Residencia Sanitaria',
                    'Hospitalizado Básico',
                    'Hospitalizado Medio',
                    'Hospitalizado UCI',
                    'Hospitalizado UTI',
                    'Hospitalizado UCI (Ventilador)'
                ])
                ->orWhereNull('status');
             })
            ->with('tracing')
            ->with('demographic')
            ->get()
            ->sortBy(function($q){
                return $q->tracing->next_control_at;
                })
            ->all();

        $establishments = Establishment::whereIn('id', auth()->user()->establishments)->distinct('name')->get();

        return view('patients.tracing.mapbyestablishment', compact('patients','establishments'));
    }

    public function tracingCompleted()
    {
        $patients = Patient::whereHas('demographic', function($q) {
            $q->whereIn('commune_id', auth()->user()->communes());
        })
            ->whereHas('tracing', function($q) {
                $q->where('status',0)
                    ->orderBy('next_control_at');
            })
            ->where(function ($q) {
                $q->whereNotIn('status',[
                    'Fallecido',
                    'Alta',
                    'Residencia Sanitaria',
                    'Hospitalizado Básico',
                    'Hospitalizado Medio',
                    'Hospitalizado UCI',
                    'Hospitalizado UTI',
                    'Hospitalizado UCI (Ventilador)'])
                    ->orWhereNull('status');
            })
            ->with('tracing')
            ->with('demographic')
            ->get()
            ->sortBy(function($q){
                return $q->tracing->next_control_at;
            })
            ->all();

        $titulo = 'Fin de Seguimiento';
        return view('patients.tracing.index', compact('patients', 'titulo'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function mapByCommune()
    {
        if(auth()->user()->establishments->count() == 0){
            session()->flash('info', 'Usuario no tiene establecimientos asociados.');
            return  redirect()->back();
        }

        $patients = Patient::whereHas('demographic', function($q) {
                $q->whereIn('commune_id', auth()->user()->communes());
            })
            ->whereHas('tracing', function($q) {
                    $q->where('status',1)
                      ->orderBy('next_control_at');
                })
            ->where(function ($q) {
                $q->whereNotIn('status',[
                    'Fallecido',
                    'Alta',
                    'Residencia Sanitaria',
                    'Hospitalizado Básico',
                    'Hospitalizado Medio',
                    'Hospitalizado UCI',
                    'Hospitalizado UTI',
                    'Hospitalizado UCI (Ventilador)'])
                  ->orWhereNull('status');
             })
            ->with('tracing')
            ->with('demographic')
            ->get()
            ->sortBy(function($q){
                return $q->tracing->next_control_at;
            })
            ->all();

        $communes = Commune::whereIn('id', auth()->user()->communes())->distinct('name')->get();



        return view('patients.tracing.mapbycommune', compact('patients','communes'));
    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tracing = new tracing($request->All());
        $tracing->user_id = auth()->id();
        $tracing->status = 1;
        $tracing->next_control_at = Carbon::now();
        $tracing->quarantine_start_at = Carbon::now();
        $tracing->quarantine_end_at = Carbon::now()->add(13,'days');
        $tracing->save();

        return redirect()->back();
    }



    public function reportByCommune(Request $request)
    {


        if($request->has('date')){
            $date = $request->get('date');
        }
        else{
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

        $from = $request->get('date'). ' 00:00:00';
        $to = $request->get('date'). ' 23:59:59';

        $patients = Patient::whereHas('suspectCases', function ($q) use($date) {
                                $q->where('pscr_sars_cov_2', 'positive')
                                ->whereDate('pscr_sars_cov_2_at', $date);
                              })
                              ->whereHas('demographic', function ($q) {
                                $q->where('region_id', env('REGION'));
                              })
                              ->get();

        foreach($patients as $patient){
            
            $report[$patient->demographic->commune_id]['positives'] += 1;

            foreach ($patient->contactPatient as $contact) {
                if($contact->patient_id == $patient->id){
                    // dd($contact);
                    $report[$patient->demographic->commune_id]['car'] += 1;
                }

                
            }         

            if($patient->tracing){
                if($patient->tracing->status == 1){
                $report[$patient->demographic->commune_id]['curso'] += 1;                
                }
                if($patient->tracing->status == null or $patient->tracing->status == 0){
                    $report[$patient->demographic->commune_id]['terminado'] += 1;
                }                   
                    
            

            }
        }

        dd($report);

        

        if ($patients->count() == 0){
            session()->flash('info', 'No existen casos positivos o no hay casos con dirección.');
            //return redirect()->route('home');
        }

        

        $communes_ids = array_map('trim',explode(",",env('COMUNAS')));
        $communes = Commune::whereIn('id', $communes_ids)->get();

        

        return view('patients.tracing.reportbycommune',compact('request','communes','patients'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tracing\Tracing  $tracing
     * @return \Illuminate\Http\Response
     */
    public function show(Tracing $tracing)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tracing\Tracing  $tracing
     * @return \Illuminate\Http\Response
     */
    public function edit(Tracing $tracing)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tracing\Tracing  $tracing
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tracing $tracing)
    {
        $tracing->fill($request->all());
        $tracing->save();

        session()->flash('info', 'Los datos de seguimiento fueron guardados');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tracing\Tracing  $tracing
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tracing $tracing)
    {
        //
    }

    public function migrate(){
        $patients = Patient::whereHas('suspectCases', function ($q) {
            $q->where('pscr_sars_cov_2','positive');
        })->with('suspectCases')->get();

        foreach($patients as $patient){
            if($patient->demographic->commune->id == 8 OR
               $patient->demographic->commune->id == 9 OR
               $patient->demographic->commune->id == 10 OR
               $patient->demographic->commune->id == 11) {
                $suspectCase                = $patient->suspectCases->where('pscr_sars_cov_2','positive')->first();
                $tracing                    = new Tracing();
                $tracing->patient_id        = $suspectCase->patient_id;
                $tracing->user_id           = ($suspectCase->user_id === 0) ? null : $suspectCase->user_id;
                $tracing->index             = 1;
                $tracing->establishment_id  = ($suspectCase->establishment_id === 0) ? 4002 : $suspectCase->establishment_id;
                $tracing->functionary       = $suspectCase->functionary;
                $tracing->gestation         = $suspectCase->gestation;
                $tracing->gestation_week    = $suspectCase->gestation_week;
                $tracing->next_control_at   = $suspectCase->pscr_sars_cov_2_at;
                $tracing->quarantine_start_at = ($suspectCase->symptoms_at) ?
                                                $suspectCase->symptoms_at :
                                                $suspectCase->pscr_sars_cov_2_at;
                $tracing->quarantine_end_at = $tracing->quarantine_start_at->add(14,'days');
                $tracing->observations      = $suspectCase->observation;
                $tracing->notification_at   = $suspectCase->notification_at;
                $tracing->notification_mechanism = $suspectCase->notification_mechanism;
                $tracing->discharged_at     = $suspectCase->discharged_at;
                $tracing->symptoms_start_at = $suspectCase->symptoms_at;
                switch ($suspectCase->symptoms) {
                    case 'Si': $tracing->symptoms = 1; break;
                    case 'No': $tracing->symptoms = 0; break;
                    default: $tracing->symptoms = null; break;
                }

                if($suspectCase->patient->status == 'Fallecido') {
                    $tracing->status = 0;
                }
                else {
                    $tracing->status = ($tracing->quarantine_start_at < Carbon::now()->sub(14,'days')) ? 0 : 1;
                }

                $tracing->save();
                echo $patient->name . '<br>';
            }
        }
    }

    public function quarantineCheck(Request $request)
    {
        $fechaActual = Carbon::now()->toDateString();
        $run = $request->run;

        $isQuarantined = Tracing::whereHas('patient', function ($q) use ($run) {
            $q->where('run', $run);
        })
        ->where('quarantine_start_at', '<=', $fechaActual)
        ->where(function($q) use ($fechaActual){
            $q->where('quarantine_end_at', '>=', $fechaActual)
                ->orWhereNull('quarantine_end_at');
        })->exists();

        return view('patients.tracing.quarantine_check', compact('isQuarantined', 'run'));
    }
}
