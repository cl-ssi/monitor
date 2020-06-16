<?php

namespace App\Http\Controllers;

use App\Statu;
use Illuminate\Http\Request;

class StatuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $status = Statu::All();
        return view('parameters.status.index', compact('status'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('parameters.status.create');
        
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
        $statu = new Statu($request->All());
        $statu->save();
        session()->flash('success', 'Se creo Estado exitosamente');
        return redirect()->route('parameters.statu');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Statu  $statu
     * @return \Illuminate\Http\Response
     */
    public function show(Statu $statu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Statu  $statu
     * @return \Illuminate\Http\Response
     */
    public function edit(Statu $statu)
    {
        //


        return view('parameters.status.edit', compact('statu'));


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Statu  $statu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Statu $statu)
    {
        //

        $statu->fill($request->all());
        $statu->save();
        session()->flash('success', 'Se modificó el estado exitosamente');
        return redirect()->route('parameters.statu');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Statu  $statu
     * @return \Illuminate\Http\Response
     */
    public function destroy(Statu $statu)
    {
        //
    }
}
