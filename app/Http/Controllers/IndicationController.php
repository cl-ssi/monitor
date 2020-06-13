<?php

namespace App\Http\Controllers;

use App\SanitaryResidence\Indication;
// use App\Log;

use Illuminate\Http\Request;

class IndicationController extends Controller
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
        if ($request->indication_id == null) {
            $indication = new Indication($request->All());
            $indication->patient_id = $indication->booking->patient->id;
            $indication->user_id = auth()->user()->id;
            $indication->save();
            session()->flash('success', 'Se guardó la información.');
        } else {
            $indication = Indication::find($request->indication_id);
            $indication->fill($request->All());
            $indication->save();
            session()->flash('success', 'Se modificó la información.');
        }

        // $logPatient = new Log();
        // $logPatient->old = clone $indication;

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SanitaryResidence\Indication  $indication
     * @return \Illuminate\Http\Response
     */
    public function show(Indication $indication)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SanitaryResidence\Indication  $indication
     * @return \Illuminate\Http\Response
     */
    public function edit(Indication $indication)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SanitaryResidence\Indication  $indication
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Indication $indication)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SanitaryResidence\Indication  $indication
     * @return \Illuminate\Http\Response
     */
    public function destroy(Indication $indication)
    {
        //
    }
}
