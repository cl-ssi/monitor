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
        $admissions = AdmissionSurvey::where('residency',true)->
            whereHas('patient', function($q){
            $q->where('status', 'Esperando Residencia Sanitaria');
        })->get();
        return view('sanitary_residences.admission.index', compact('admissions'));
    }

    public function inbox()
    {       
        $admissions = AdmissionSurvey::whereNotNull('residency')->
        whereHas('patient', function($q){
            $q->where('status','<>' ,'Esperando Residencia Sanitaria');
        })->get();

        //dd($admissions);
        return view('sanitary_residences.admission.inbox', compact('admissions'));

    }

    public function changestatus(AdmissionSurvey $admission)
    {       
        //dd($admission);
        $admission->patient->status = 'Esperando Residencia Sanitaria';
        $admission->patient->save();
        session()->flash('success', 'Se Aprobo exitosamente para Residencia Sanitaria');
        return redirect()->route('sanitary_residences.admission.inbox');
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
        session()->flash('success', 'Encuesta Realizada Exitosamente a'.$admission->patient->name);
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
