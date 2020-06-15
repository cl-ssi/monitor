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
        
        //$admissions = AdmissionSurvey::where('residency',true)->get();
        //$admissions = AdmissionSurvey::where('residency',1);
        $admissions = AdmissionSurvey::where('residency',true)->
            whereHas('patient', function($q){
            $q->where('status', 'Aprobado Residencia Sanitaria');
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
        session()->flash('success', 'Encuesta Realizada Esperando Visto Bueno en caso que sea necesario');
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
    public function edit(AdmissionSurvey $admission)
    {
        //
        return view('sanitary_residences.admission.edit',compact('admission'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SanitaryResidence\AdmissionSurvey  $admissionSurvey
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AdmissionSurvey $admission)
    {
        //
        $admission->fill($request->all());
        if($request->residency==1)
        {
        $admission->patient->status = 'Aprobado Residencia Sanitaria';
        $admission->patient->save();
        }        
        
        $admission->save();
        session()->flash('success', 'Cambios realizados ');
        return redirect()->route('patients.index');
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
