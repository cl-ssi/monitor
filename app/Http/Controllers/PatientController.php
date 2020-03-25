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

        $log = Log::CREATE([
            'old' => NULL,
            'new' => json_encode($patient),
            'diferences' => NULL,
            'model_id' => $patient->id,
            'model_type'=> 'App\Patient',
            'user_id'   => Auth::id()
        ]);

        // $demographic = new Demographic($request->All());
        // $demographic->save();
        //
        // $log = Log::CREATE([
        //     'old' => NULL,
        //     'new' => json_encode($demographic),
        //     'diferences' => NULL,
        //     'model_id' => $demographic->id,
        //     'model_type'=> 'App\Demographic',
        //     'user_id'   => Auth::id()
        // ]);

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
        //$patient_old = clone $patient;
        //$demographic_old = clone $patient->demographic;
        if($patient->demographic) {
            $patient->fill($request->all())->demographic->fill($request->all());
            $patient->demographic->save();
        }
        else {
            $patient->fill($request->all());
            $demographic = new Demographic($request->All());
            $patient->demographic()->save($demographic);
        }
        $patient->save();

        // $log = Log::CREATE([
        //     'old' => json_encode($patient_old),
        //     'new' => json_encode($patient),
        //     'diferences' => NULL,
        //     'model_id' => $patient->id,
        //     'model_type'=> 'App\Patient',
        //     'user_id'   => Auth::id()
        // ]);
        //
        // $log = Log::CREATE([
        //     'old' => json_encode($demographic_old),
        //     'new' => json_encode($patient->demographic),
        //     'diferences' => NULL,
        //     'model_id' => $patient->demographic->id,
        //     'model_type'=> 'App\Demographic',
        //     'user_id'   => Auth::id()
        // ]);

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
        $patient->delete();

        return redirect()->route('patients.index');
    }

    public function getPatient($rut)
    {
        $patient = Patient::where('run',$rut)->first();
        if($patient==null){return 0;}
        return $patient;
    }
}
