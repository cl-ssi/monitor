<?php

namespace App\Http\Controllers;

use App\SampleOrigin;
use Illuminate\Http\Request;

class SampleOriginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sampleOrigins = SampleOrigin::orderBy('name')->get();
        return view('lab.sample_origins.index', compact('sampleOrigins'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('lab.sample_origins.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $sampleOrigin = new SampleOrigin($request->All());
        $sampleOrigin->save();

        return redirect()->route('lab.sample_origins.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SampleOrigin  $sampleOrigin
     * @return \Illuminate\Http\Response
     */
    public function show(SampleOrigin $sampleOrigin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SampleOrigin  $sampleOrigin
     * @return \Illuminate\Http\Response
     */
    public function edit(SampleOrigin $sampleOrigin)
    {
        return view('lab.sample_origins.edit', compact('sampleOrigin'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SampleOrigin  $sampleOrigin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SampleOrigin $sampleOrigin)
    {
        $sampleOrigin->fill($request->all());
        $sampleOrigin->save();

        return redirect()->route('lab.sample_origins.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SampleOrigin  $sampleOrigin
     * @return \Illuminate\Http\Response
     */
    public function destroy(SampleOrigin $sampleOrigin)
    {
        //
    }
}
