<?php

namespace App\Http\Controllers;

use App\Dialysis\DialysisPatient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DialysisPatientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $dialysis_patients = DialysisPatient::where('dialysis_center_id', Auth::user()->dialysis_center_id)->get();
        return view('lab.dialysis.index',compact('dialysis_patients'));
    }


    public function covid()
    {
        //
        $dialysis_patients = DialysisPatient::where('dialysis_center_id', Auth::user()->dialysis_center_id)->get();
        return view('lab.dialysis.covid',compact('dialysis_patients'));
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
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\DialysisPatient  $dialysisPatient
     * @return \Illuminate\Http\Response
     */
    public function show(DialysisPatient $dialysisPatient)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DialysisPatient  $dialysisPatient
     * @return \Illuminate\Http\Response
     */
    public function edit(DialysisPatient $dialysisPatient)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DialysisPatient  $dialysisPatient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DialysisPatient $dialysisPatient)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DialysisPatient  $dialysisPatient
     * @return \Illuminate\Http\Response
     */
    public function destroy(DialysisPatient $dialysisPatient)
    {
        //
    }
}
