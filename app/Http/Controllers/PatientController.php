<?php

namespace App\Http\Controllers;

use App\SuspectCase;
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
    public function index(Request $request)
    {
        $patients = Patient::search($request->input('search'))
                        ->with('demographic')
                        ->with('suspectCases')
                        ->orderBy('name')
                        ->paginate(250);
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
            $logDemographic->save();
        }
        else {

          if ($request->address != null | $request->address != null | $request->deparment != null |
              $request->town != null | $request->latitude != null | $request->longitude != null |
              $request->email != null | $request->telephone != null | $request->number != null |
              $request->region != null | $request->commune != null) {

                $demographic = new Demographic($request->All());
                $demographic->patient_id = $patient->id;
                $demographic->save();

                $logDemographic->new = $demographic;
                $logDemographic->save();
              }
        }
        //$logDemographic->save();

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

    public function positives(Request $request)
    {
        $patients = Patient::whereHas('suspectCases', function ($q) {
            $q->where('pscr_sars_cov_2','positive');
        })->with('demographic')->get();

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

        return view('patients.positives', compact('patients'));
    }


    public function getPatient($rut)
    {
        return Patient::where('run',$rut)->first();
    }

    public function georeferencing()
    {
        $suspectCases = SuspectCase::latest('id')->get();

        $data = array();
        foreach ($suspectCases as $key => $case) {
          if ($case->pscr_sars_cov_2 == 'positive' || $case->pscr_sars_cov_2 == 'pending') {
              // FIX , pendiente ver que pasó que hay un caso sin paciente asociado
              if($case->patient) {
                  if ($case->patient->demographic != null) {
                    $data[$case->patient->demographic->address . " " . $case->patient->demographic->number . ", " . $case->patient->demographic->commune][$case->patient->run]['paciente']=$case;
                  }
              }

          }
        }
        //dd($data);
        // foreach ($data as $key1 => $data1) {
        //   foreach ($data1 as $key2 => $data2) {
        //     foreach ($data2 as $key3 => $data3) {
        //       print_r($data3->patient->demographic->latitude);
        //     }
        //   }
        // }

        return view('patients.georeferencing.georeferencing', compact('suspectCases','data'));
    }
}
