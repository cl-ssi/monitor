<?php

namespace App\Http\Controllers;

use App\Tracing\Tracing;
use Illuminate\Http\Request;
use App\Patient;
use Carbon\Carbon;

class TracingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexByCommune()
    {
        // TODO: Falta chequear que tenga algun establecimiento asociado

        $patients = Patient::whereHas('demographic', function($q) {
                $q->whereIn('commune_id', auth()->user()->communes());
            })
            ->whereHas('tracing', function($q) {
                    $q->where('status',1)
                      ->orderBy('next_control_at');
                })
            ->where(function ($q) {
                $q->whereNotIn('status',['Fallecido','Alta','Residencia Sanitaria','Hospitalizado Básico','Hospitalizado medio','Hospitalizado UCI','Hospitalizado UTI','Hospitalizado UTI (Ventilador)'])
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
                $q->whereNotIn('status',['Fallecido','Alta','Residencia Sanitaria','Hospitalizado Básico','Hospitalizado medio','Hospitalizado UCI','Hospitalizado UTI','Hospitalizado UTI (Ventilador)'])
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        $tracing->next_control_at = Carbon::now()->add(1,'day');
        $tracing->save();

        return redirect()->back();
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
            $case = $patient->suspectCases->where('pscr_sars_cov_2','positive')->first();
            $tracing                    = new Tracing();
            $tracing->patient_id        = $case->patient_id;
            $tracing->user_id           = ($case->user_id === 0) ? null : $case->user_id;
            $tracing->index             = 1;
            $tracing->establishment_id  = ($case->establishment_id === 0) ? 4002 : $case->establishment_id;
            $tracing->functionary       = $case->functionary;
            $tracing->gestation         = $case->gestation;
            $tracing->gestation_week    = $case->gestation_week;
            $tracing->next_control_at   = $case->pscr_sars_cov_2_at->add(1,'day');
            $tracing->quarantine_start_at = ($case->symptoms_at) ?
                                            $case->symptoms_at :
                                            $case->pscr_sars_cov_2_at;
            $tracing->quarantine_end_at = $tracing->quarantine_start_at->add(14,'days');
            $tracing->observations = $case->observation;
            $tracing->notification_at = $case->notification_at;
            $tracing->notification_mechanism = $case->notification_mechanism;
            $tracing->discharged_at = $case->discharged_at;
            $tracing->symptoms_start_at = $case->symptoms_at;
            switch ($case->symptoms) {
                case 'Si': $tracing->symptoms = 1; break;
                case 'No': $tracing->symptoms = 0; break;
                default: $tracing->symptoms = null; break;
            }

            if($case->patient->status == 'Fallecido') {
                $tracing->status = 0;
            }
            else {
                $tracing->status = ($tracing->quarantine_end_at < Carbon::now()->sub(1,'days')) ? 0 : 1;
            }

            $tracing->save();
            echo $patient->name . '<br>';
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
