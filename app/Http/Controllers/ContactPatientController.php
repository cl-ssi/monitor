<?php

namespace App\Http\Controllers;

use App\ContactPatient;
use App\Patient;
use Illuminate\Http\Request;

class ContactPatientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $search, $id)
    {
        $id_patient = $id;
        $s = $search;
        if($request->input('search') != null){
          $run = $request->input('search');
        }
        else {
          $run = '';
        }

        $patients = Patient::where('run', $run)
                    ->orWhere('other_identification', $run)
                    ->with('demographic')
                    ->with('suspectCases')
                    ->get();

        return view('patients.contact.create', compact('patients', 's', 'id_patient','request'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // GUARDAR RELACION PACIENTE
        $contactPatient = new ContactPatient($request->All());
        $contactPatient->save();

        // GUARDAR RELACION INVERSA PACIENTE
        $patient_id = $request->get('contact_id');
        $contact_id = $request->get('patient_id');
        $relationship = $request->get('relationship');

        $patient = Patient::where('id', $request->get('patient_id'))->first();

        $inverse_relationship = $this->inverse_realtionship($relationship, $patient);

        $contactPatient = new ContactPatient($request->All());
        $contactPatient->patient_id = $patient_id;
        $contactPatient->contact_id = $contact_id;
        $contactPatient->relationship = $inverse_relationship;
        $contactPatient->comment = $request->get('comment');

        $contactPatient->save();

        $id = $request->get('patient_id');
        return redirect()->route('patients.edit', $id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ContactPatient  $contactPatient
     * @return \Illuminate\Http\Response
     */
    public function show(ContactPatient $contactPatient)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ContactPatient  $contactPatient
     * @return \Illuminate\Http\Response
     */
    public function edit(ContactPatient $contactPatient)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ContactPatient  $contactPatient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ContactPatient $contactPatient)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ContactPatient  $contactPatient
     * @return \Illuminate\Http\Response
     */
    public function destroy(ContactPatient $contactPatient)
    {
        //
    }

    public function inverse_realtionship($relationship, $patient){
        if($patient->gender == 'male'){
          switch ($relationship) {
              case "son":
                  return 'father';
                  break;
          }

        }
        // return $relationship.' hola';
    }
}
