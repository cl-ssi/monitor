<?php

namespace App\Http\Controllers;

use App\SanitaryResidence\AdmissionSurvey;
use App\Patient;
use Illuminate\Http\Request;

class AdmissionSurveyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        //$admissions = AdmissionSurvey::All();
        
        //$admissions = AdmissionSurvey::where('residency',1);
        //$admissions = AdmissionSurvey::where('residency',1);
        $admissions = AdmissionSurvey::where('residency',true)->
            whereHas('patient', function($q){
            $q->where('status', 'Esperando Residencia Sanitaria');
        })->get();
        return view('sanitary_residences.admission.index', compact('admissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Patient $patient)
    {
        //

        return view('sanitary_residences.admission.create',compact('patient'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $admission = new AdmissionSurvey($request->All());
        $admission->user_id = auth()->user()->id;
        if($request->residency)
        {
        $admission->patient->status = 'Esperando Residencia Sanitaria';
        $admission->patient->save();
        }
        
        
        $admission->save();
        session()->flash('success', 'Se Añadió la Encuesta de Habitabilidad exitosamente');        
        return redirect()->route('patients.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SanitaryResidence\AdmissionSurvey  $admissionSurvey
     * @return \Illuminate\Http\Response
     */
    public function show(AdmissionSurvey $admissionSurvey)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SanitaryResidence\AdmissionSurvey  $admissionSurvey
     * @return \Illuminate\Http\Response
     */
    public function edit(AdmissionSurvey $admissionSurvey)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SanitaryResidence\AdmissionSurvey  $admissionSurvey
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AdmissionSurvey $admissionSurvey)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SanitaryResidence\AdmissionSurvey  $admissionSurvey
     * @return \Illuminate\Http\Response
     */
    public function destroy(AdmissionSurvey $admissionSurvey)
    {
        //
    }
}
