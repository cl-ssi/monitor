<?php

namespace App\Http\Controllers;

use App\SanitaryResidence\Residence;
use App\SanitaryResidence\Booking;
use App\SanitaryResidence\Room;
use App\Patient;
use App\Log;
use App\SuspectCase;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Residence $residence)
    {
        $rooms = $rooms = Room::where('residence_id',$residence->id)->orderBy('floor')->get();
        $bookings = Booking::All();
        return view('sanitary_residences.bookings.index', compact('residence','bookings', 'rooms'));
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
        return view('sanitary_residences.bookings.create', compact('patients', 'rooms'));
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
        if ($request->released_cause == null)
        {
            $booking = new Booking($request->All());
            $booking->status = 'Residencia Sanitaria';
            //$booking->patient->suspectCases->last()->status = 'Residencia Sanitaria';
            $booking->patient->status = 'Residencia Sanitaria';
            $booking->patient->save();
            $booking->save();
            session()->flash('success', 'Booking creado Exitosamente');
        }
        else
        {
          $booking = Booking::find($request->booking_id);
          //$booking->patient->suspectCases->last()->status = $request->status;
          $booking->patient->status = $request->status;
          $booking->patient->save();
          //$booking->patient->suspectCases->last()->save();
          $booking->fill($request->All());
          $booking->save();
          session()->flash('success', 'Paciente dado de Alta Exitosamente');
        }
        return redirect()->route('sanitary_residences.bookings.index',1);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SanitaryResidence\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function show(Booking $booking)
    {
        $patients = Patient::orderBy('name')->get();
        $rooms = Room::All();
        return view('sanitary_residences.bookings.show', compact('booking', 'patients', 'rooms'));
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
        $logPatient = new Log();
        $logPatient->old = clone $booking;

        $booking->fill($request->all());
        $booking->save();

        session()->flash('success', 'Se modificó la información.');

        // return redirect()->route('sanitary_residences.bookings.index');

        $patients = Patient::orderBy('name')->get();
        $rooms = Room::All();
        return view('sanitary_residences.bookings.show', compact('booking', 'patients', 'rooms'));
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

    public function excel(Booking $booking)
    {

        return view('sanitary_residences.bookings.excel.excel', compact('booking'));
    }

    public function excelall()
    {
        $residences = Residence::All();
        //$bookings = Booking::all();
        $bookings = Booking::where('status', 'Residencia Sanitaria')->get();
        return view('sanitary_residences.bookings.excel.excelall', compact('residences','bookings'));
    }
}
