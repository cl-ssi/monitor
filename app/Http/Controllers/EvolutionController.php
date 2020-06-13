<?php

namespace App\Http\Controllers;

use App\SanitaryResidence\Evolution;
// use App\Log;

use Illuminate\Http\Request;

class EvolutionController extends Controller
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
    public function create()
    {
        //
        return view('sanitary_residences.evolutions.create');
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
        if ($request->evolution_id == null) {
            $evolution = new Evolution($request->All());
            $evolution->patient_id = $evolution->booking->patient->id;
            $evolution->user_id = auth()->user()->id;
            $evolution->save();
            session()->flash('success', 'Se guardó la información.');
        } else {
            $evolution = Evolution::find($request->evolution_id);
            $evolution->fill($request->All());
            $evolution->save();
            session()->flash('success', 'Se modificó la información.');
        }

        // $logPatient = new Log();
        // $logPatient->old = clone $evolution;

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\sanitary_residences\evolution  $evolution
     * @return \Illuminate\Http\Response
     */
    public function show(evolution $evolution)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\sanitary_residences\evolution  $evolution
     * @return \Illuminate\Http\Response
     */
    public function edit(evolution $evolution)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\sanitary_residences\evolution  $evolution
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, evolution $evolution)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\sanitary_residences\evolution  $evolution
     * @return \Illuminate\Http\Response
     */
    public function destroy(evolution $evolution)
    {
        //
    }
}
