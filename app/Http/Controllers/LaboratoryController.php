<?php

namespace App\Http\Controllers;

use App\Laboratory;
use App\Commune;
use App\User;
use Illuminate\Http\Request;

class LaboratoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $laboratories = Laboratory::All();
        return view('parameters.lab.index', compact('laboratories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //$communes = Commune::All();
        $communes_ids = array_map('trim', explode(",", env('COMUNAS')));
        $communes = Commune::whereIn('id', $communes_ids)->get();
        $users = User::All();
        return view('parameters.lab.create', compact('communes', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $laboratory = new Laboratory($request->All());
        $laboratory->save();
        session()->flash('success', 'Se creo laboratorio exitosamente');
        return redirect()->route('parameters.lab');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Laboratory  $laboratory
     * @return \Illuminate\Http\Response
     */
    public function show(Laboratory $laboratory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Laboratory  $laboratory
     * @return \Illuminate\Http\Response
     */
    public function edit(Laboratory $laboratory)
    {
        //$communes = Commune::All();
        $communes_ids = array_map('trim', explode(",", env('COMUNAS')));
        $communes = Commune::whereIn('id', $communes_ids)->get();
        $users = User::All();
        return view('parameters.lab.edit', compact('laboratory','communes', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Laboratory  $laboratory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Laboratory $laboratory)
    {
        $laboratory->fill($request->all());
        $laboratory->save();
        session()->flash('success', 'Se modificó el laboratorio exitosamente');
        return redirect()->route('parameters.lab');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Laboratory  $laboratory
     * @return \Illuminate\Http\Response
     */
    public function destroy(Laboratory $laboratory)
    {
        //
    }
}
