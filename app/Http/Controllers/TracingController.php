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
        $patients = Patient::whereHas('demographic', function($q) {
                $q->whereIn('commune_id', auth()->user()->communes());
            })
            ->whereHas('tracing', function($q) {
                    $q->where('status',1)
                    ->orderBy('next_control_at');
                })
            ->with('tracing')
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
            ->with('tracing')
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
            $tracing = new Tracing();
            $tracing->patient_id = $case->patient_id;
            $tracing->user_id = ($case->user_id === 0) ? null : $case->user_id;
            $tracing->index = 1;
            $tracing->establishment_id = ($case->establishment_id === 0) ? 4002 : $case->establishment_id;
            switch ($case->symptoms) {
                case 'Si':
                    $symptom = 1;
                    break;
                case 'No':
                    $symptom = 0;
                default:
                    $symptom = null;
            }
            $tracing->symptoms = $symptom;
            $tracing->functionary = $case->functionary;
            $tracing->gestation = $case->gestation;
            $tracing->gestation_week = $case->gestation_week;
            $tracing->next_control_at = $case->pscr_sars_cov_2_at->add(1,'day');
            $tracing->quarantine_start_at = $case->pscr_sars_cov_2_at;
            $tracing->quarantine_end_at = $case->pscr_sars_cov_2_at->add(14,'days');
            $tracing->observations = $case->observation;
            if($patient->status != 'Hospitalizado UCI (Ventilador)'
                AND $patient->status != 'Hospitalizado Básico'
                AND $patient->status != 'Hospitalizado Medio'
                AND $patient->status != 'Hospitalizado UTI'
                AND $patient->status != 'Hospitalizado UCI'
                AND $patient->status != 'Residencia Sanitaria')

                if($tracing->quarantine_end_at < Carbon::now()->sub(1,'days')) {
                    $tracing->status = 0;
                }
                else {
                    $tracing->status = 1;
                }
            else if($patient->status != 'Fallecido' ) {
                $tracing->status = 0;
            }

            $tracing->save();
            echo $patient->name . '<br>';
        }
    }
}
