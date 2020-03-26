<?php

namespace App\Http\Controllers;

use App\Patient;
use App\Demographic;
use App\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $patients = Patient::orderBy('name')->get();
        return view('patients.index', compact('patients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('patients.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $patient = new Patient($request->All());
        $patient->save();

        $log = new Log();
        //$log->old = $patient;
        $log->new = $patient;
        $log->save();

        return redirect()->route('patients.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function show(Patient $patient)
    {
        return view('patients.show', compact('patient'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function edit(Patient $patient)
    {
        return view('patients.edit',compact('patient'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Patient $patient)
    {
        $logPatient = new Log();
        $logPatient->old = clone $patient;

        $patient->fill($request->all());
        $patient->save();

        $logPatient->new = $patient;
        $logPatient->save();

        $logDemographic = new Log();
        if($patient->demographic) {
            $logDemographic->old = clone $patient->demographic;
            $patient->demographic->fill($request->all());
            $patient->demographic->save();
            $logDemographic->new = $patient->demographic;
        }
        else {
            $demographic = new Demographic($request->All());
            $demographic->patient_id = $patient->id;
            $demographic->save();

            $logDemographic->new = $demographic;
        }
        $logDemographic->save();

        return redirect()->route('patients.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function destroy(Patient $patient)
    {
        $log = new Log();
        $log->old = clone $patient;
        $log->new = $patient->setAttribute('patient','delete');
        $log->save();

        $patient->delete();

        return redirect()->route('patients.index');
    }

    public function getPatient($rut)
    {
        return Patient::where('run',$rut)->first();
        // if($patient==null){return 0;}
        // return $patient;
    }
}
