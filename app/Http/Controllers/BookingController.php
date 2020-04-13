<?php

namespace App\Http\Controllers;

use App\SanitaryResidence\Booking;
use App\SanitaryResidence\Room;
use App\Patient;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rooms = Room::All();
        $bookings = Booking::all();
        return view('sanitary_residences.bookings.index', compact('bookings','rooms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $patients = Patient::orderBy('name')->get();
        $rooms = Room::All();
        return view('sanitary_residences.bookings.create', compact('patients','rooms'));


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
        $booking = new Booking($request->All());
        $booking->save();

        return redirect()->route('sanitary_residences.bookings.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SanitaryResidence\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function show(Booking $booking)
    {
        return view('sanitary_residences.bookings.show', compact('booking'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SanitaryResidence\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function edit(Booking $booking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SanitaryResidence\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Booking $booking)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SanitaryResidence\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function destroy(Booking $booking)
    {
        //
    }
}
