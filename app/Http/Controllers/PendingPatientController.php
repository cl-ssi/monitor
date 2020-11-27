<?php

namespace App\Http\Controllers;

use App\Commune;
use App\Country;
use App\PendingPatient;
use App\Region;
use App\Specialty;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PendingPatientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $communes_ids = Auth::user()->communes();

        $selectedStatus = $request->get('status');
        if(!$selectedStatus){
            $selectedStatus = 'not_contacted';
        }

        if(Auth::user()->can('NotContacted: show all')){
            $pendingPatients = PendingPatient::where('status', $selectedStatus)
                ->get();
        }else{
            $pendingPatients = PendingPatient::where('status', $selectedStatus)
                ->whereIn('commune_id',  $communes_ids)
                ->get();
        }

        return view('pending_patient.index', compact('pendingPatients', 'selectedStatus'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $regions = Region::orderBy('id','ASC')->get();
        $communes = Commune::orderBy('id','ASC')->get();
        $specialties = Specialty::all();
        return view('pending_patient.create', compact('regions', 'communes', 'specialties'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $patientPendingList = new PendingPatient($request->All());
        $patientPendingList->save();
        session()->flash('success', "Se ha ingresado correctamente el paciente");
        return redirect()->back();
//        return view('pending_patient.create', compact('regions', 'communes'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(PendingPatient $pendingPatient)
    {
        $regions = Region::orderBy('id','ASC')->get();
        $communes = Commune::orderBy('id','ASC')->get();
        $specialties = Specialty::all();
        return view('pending_patient.edit', compact('pendingPatient', 'regions', 'communes', 'specialties'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PendingPatient $pendingPatient)
    {
        $pendingPatient->fill($request->all());
        $pendingPatient->save();
        session()->flash('success', 'Se actualizó correctamente la información del paciente');
        return redirect()->route('pending_patient.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
