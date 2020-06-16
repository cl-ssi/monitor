<?php

namespace App\Http\Controllers;

use App\Tracing\EventType;
use Illuminate\Http\Request;

class EventTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $event_types = EventType::orderby('name', 'ASC')
            ->get();
            
        return view('parameters.event_type.index', compact('event_types'));
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        return view('parameters.event_type.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $event_types = new EventType($request->All());
        $event_types->save();
        session()->flash('success', '¡Tipo de evento creado con éxito!');
        return redirect()->route('parameters.EventType');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tracing\EventType  $eventType
     * @return \Illuminate\Http\Response
     */
    public function show(EventType $eventType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tracing\EventType  $eventType
     * @return \Illuminate\Http\Response
     */
    public function edit(EventType $eventType)
    {
        return view('parameters.event_type.edit', compact('eventType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tracing\EventType  $eventType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EventType $eventType)
    {
        $eventType->fill($request->all());
        $eventType->save();
        session()->flash('success', '¡Tipo de evento modificado exitosamente!');
        return redirect()->route('parameters.EventType');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tracing\EventType  $eventType
     * @return \Illuminate\Http\Response
     */
    public function destroy(EventType $eventType)
    {
        //
    }
}
