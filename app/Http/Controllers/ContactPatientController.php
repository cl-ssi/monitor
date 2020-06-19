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
        //BANDERAS DE BUSQUEDA
        $id_patient = $id;
        $s = $search;
        $message = '';

        //BUSQUEDA DE PACIENTE
        $patients = Patient::where('id', $id)
                    ->with('demographic')
                    ->with('suspectCases')
                    ->get();

        //BUSQUEDA DE CONTACTO
        if($request->input('search') != null){
          $run = $request->input('search');
        }
        else {
          $run = '';
        }
        $contacts = Patient::where('run', $run)
                    ->orWhere('other_identification', $run)
                    ->with('demographic')
                    ->with('suspectCases')
                    ->get();
        $message = 'new contact';

        if($contacts->isEmpty()){
          $message = 'dont exist';
        }

        foreach ($contacts as $key => $contact) {
          if($contact->id == $id){
            $message = 'same patient';
          }
        }

        if($search == 'search_true'){
          //CONTACTO EXISTENTE
          $contactPatients = ContactPatient::where('patient_id', $id_patient)->get();
          foreach ($contactPatients as $key => $contactPatient) {
            foreach ($contacts as $key => $contact) {
              if($contactPatient->contact_id == $contact->id){
                  $message = 'contact already registered';
              }
            }

          }
        }


        return view('patients.contact.create', compact('patients', 'contacts','s', 'id_patient','request', 'message'));
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

        $patient = Patient::where('id', $request->get('patient_id'))->first();

        $contactPatient = new ContactPatient($request->All());
        $contactPatient->patient_id = $request->get('contact_id');
        $contactPatient->contact_id = $request->get('patient_id');
        $contactPatient->last_contact_at =  $request->get('last_contact_at');
        $contactPatient->category = $request->get('category');
        $contactPatient->relationship = $request->get('relationship');
        $contactPatient->live_together = $request->get('live_together');
        $contactPatient->comment = $request->get('comment');
        $contactPatient->index = NULL;

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
        return view('patients.contact.edit', compact('contactPatient'));
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
        $contactPatient->fill($request->all());
        $contactPatient->save();



        return redirect()->route('patients.edit', $contactPatient->patient->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ContactPatient  $contactPatient
     * @return \Illuminate\Http\Response
     */
    public function destroy(ContactPatient $contactPatient)
    {

        $patient = $contactPatient->patient_id;
        $contact = $contactPatient->contact_id;

        $contactPatient->delete();

        $contact = ContactPatient::where('contact_id', $patient)
            ->where('patient_id', $contact)
            ->delete();

        return redirect()->route('patients.edit', $patient);
    }

}
