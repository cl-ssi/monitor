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
        $id = $request->get('patient_id');
        $contactPatient = new ContactPatient($request->All());
        $contactPatient->save();

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
}
