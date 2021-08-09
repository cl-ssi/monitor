<?php

namespace App\Http\Controllers;

use App\SequencingCriteria;
use App\SuspectCase;
use Illuminate\Http\Request;

class SequencingCriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $sequencingcriterias = SequencingCriteria::whereNull('send_at')->get();
        return view('lab.sequencing.index', compact('sequencingcriterias'));
    }

    public function indexsend()
    {
        //
        //$sequencingcriterias = SequencingCriteria::All();
        $sequencingcriterias = SequencingCriteria::whereNotNull('send_at')->get();
        //$texto='Enviados a Secuenciación';
        $send=1;
        return view('lab.sequencing.index', compact('sequencingcriterias','send'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(SuspectCase $suspect_case)
    {
        //
        
        return view('lab.sequencing.create', compact('suspect_case'));
    }

    public function send(SequencingCriteria $sequencingCriteria)
    {
                
        $sequencingCriteria->send_at = now();
        $sequencingCriteria->save();
        session()->flash('success', 'Se envío la muestra a secuenciación');
        return redirect()->route('sequencing.index');
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
     * @param  \App\SequencingCriteria  $sequencingCriteria
     * @return \Illuminate\Http\Response
     */
    public function show(SequencingCriteria $sequencingCriteria)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SequencingCriteria  $sequencingCriteria
     * @return \Illuminate\Http\Response
     */
    public function edit(SequencingCriteria $sequencingCriteria)
    {
        //
        return view('lab.sequencing.edit', compact('sequencingCriteria'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SequencingCriteria  $sequencingCriteria
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SequencingCriteria $sequencingCriteria)
    {
        //
        $sequencingCriteria->fill($request->all());
        $sequencingCriteria->save();
        session()->flash('success', 'Se guardaron los datos exitosamente.');
        return redirect()->route('sequencing.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SequencingCriteria  $sequencingCriteria
     * @return \Illuminate\Http\Response
     */
    public function destroy(SequencingCriteria $sequencingCriteria)
    {
        //
        $sequencingCriteria->delete();
        session()->flash('success', 'Se eliminaron los criterios de secuenciación exitosamente');
        return redirect()->route('sequencing.index');

    }
}
