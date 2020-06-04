<?php

namespace App\Http\Controllers;

use App\InmunoTest;
use App\Patient;
use Illuminate\Http\Request;

class InmunoTestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('inmuno_tests.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $search)
    {
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

        return view('inmuno_tests.create', compact('patients', 's'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inmunoTest = new InmunoTest($request->All());
        $inmunoTest->save();

        return redirect()->route('lab.inmuno_tests.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\InmunoTest  $inmunoTest
     * @return \Illuminate\Http\Response
     */
    public function show(InmunoTest $inmunoTest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\InmunoTest  $inmunoTest
     * @return \Illuminate\Http\Response
     */
    public function edit(InmunoTest $inmunoTest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\InmunoTest  $inmunoTest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InmunoTest $inmunoTest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\InmunoTest  $inmunoTest
     * @return \Illuminate\Http\Response
     */
    public function destroy(InmunoTest $inmunoTest)
    {
        //
    }
}
