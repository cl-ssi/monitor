<?php

namespace App\Http\Controllers;

use App\SanitaryResidence\Room;
use Illuminate\Http\Request;
use App\SanitaryResidence\Residence;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$rooms = Room::all();
        $rooms = Room::orderBy('residence_id','ASC')->orderBy('floor','ASC')->orderBy('number','ASC')->get();
        return view('sanitary_residences.rooms.index', compact('rooms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $residences = Residence::All();
        return view('sanitary_residences.rooms.create', compact('residences'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $room = new Room($request->All());
        $room->save();

        session()->flash('success', 'Se creo la habitación exitosamente');
        return redirect()->route('sanitary_residences.rooms.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SanitaryResidence\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function show(Room $room)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SanitaryResidence\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function edit(Room $room)
    {
        $residences = Residence::All();
        return view('sanitary_residences.rooms.edit', compact('room','residences'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SanitaryResidence\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Room $room)
    {
        $room->fill($request->all());
        $room->save();
        session()->flash('success', 'Se modificó la habitación exitosamente');
        return redirect()->route('sanitary_residences.rooms.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SanitaryResidence\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function destroy(Room $room)
    {
        //

        $room->delete();
        session()->flash('success', 'Cuarto Eliminado Exitosamente');
        return redirect()->route('sanitary_residences.rooms.index');
    }
}
