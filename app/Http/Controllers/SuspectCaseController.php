<?php

namespace App\Http\Controllers;

use App\SuspectCase;
use App\Patient;
use Illuminate\Http\Request;

class SuspectCaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $suspectCases = SuspectCase::latest('id')->get();
        return view('lab.suspect_cases.index', compact('suspectCases'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('lab.suspect_cases.create');
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

        $suspectCase = new SuspectCase($request->All());
        $patient->suspectCases()->save($suspectCase);

        //$suspectCase->save();

        return redirect()->route('lab.suspect_cases.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SuspectCase  $suspectCase
     * @return \Illuminate\Http\Response
     */
    public function show(SuspectCase $suspectCase)
    {
        return view('lab.suspect_cases.show', compact('suspectCase'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SuspectCase  $suspectCase
     * @return \Illuminate\Http\Response
     */
    public function edit(SuspectCase $suspectCase)
    {
        return view('lab.suspect_cases.edit', compact('suspectCase'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SuspectCase  $suspectCase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SuspectCase $suspectCase)
    {
        $suspectCase->fill($request->all());

        $suspectCase->save();

        return redirect()->route('lab.suspect_cases.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SuspectCase  $suspectCase
     * @return \Illuminate\Http\Response
     */
    public function destroy(SuspectCase $suspectCase)
    {
        $suspectCase->delete();

        return redirect()->route('lab.suspect_cases.index');
    }

    public function report() {
        $cases = SuspectCase::All();
        return view('lab.suspect_cases.report', compact('cases'));
    }
}
