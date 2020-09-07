<?php

namespace App\Http\Controllers;

use App\SanitaryResidence\Residence;
use App\SanitaryResidence\Booking;
use App\SanitaryResidence\Room;
use App\SanitaryResidence\VitalSign;
use App\Patient;
//use App\Log;
use App\SuspectCase;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Residence $residence)
    {
        $rooms = $rooms = Room::where('residence_id',$residence->id)->orderBy('floor')->orderBy('number')->get();

        $bookings = Booking::All();
        $releases = Booking::whereNotNull('real_to')->whereHas('room', function ($q) use($residence)
        {
            $q->where('residence_id', $residence->id);
        })->get();

        return view('sanitary_residences.bookings.index', compact('residence','bookings', 'rooms','releases'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(request $request)
    {
        //
        //$patients = Patient::whereNotIn('status', ['Alta', 'Fallecido', 'Hospitalizado UCI (Ventilador)'])->orWhereNull('status')->orderBy('name')->get();
        //$patients = Patient::All()->orderBy('name')->get();
        //$patients = Patient::orderBy('name')->get();
        //$patients = Patient::whereNotIn('status', ['Alta', 'Fallecido', 'Hospitalizado UCI (Ventilador)'])->orWhereNull('status')->orderBy('name')->get();
        //$patients = Patient::whereIn('status', ['Ambulatorio','Otra Institución','null',])->orderBy('name')->get();
        //$patients = Patient::whereIn('status', ['Ambulatorio','Otra Institución','null'])->orderBy('name')->get();
        //$patients = Patient::WhereNull('status')->orWhereIn('status', ['Ambulatorio','Otra Institución','Hospitalizado Básico'])->orderBy('name')->get();
        //$patients = Patient::WhereNull('status')->orWhereIn('status', ['Ambulatorio','Otra Institución'])->orderBy('name')->get();
        $patients = Patient::WhereNull('status')->orderBy('name')->get();
        return view('sanitary_residences.bookings.create', compact('patients','request'));
    }



    public function createfrompatient(Patient $patient, request $request)
    {
        //
        //$patients = Patient::whereNotIn('status', ['Alta', 'Fallecido', 'Hospitalizado UCI (Ventilador)'])->orWhereNull('status')->orderBy('name')->get();
        //$patients = Patient::All()->orderBy('name')->get();
        //$patients = Patient::orderBy('name')->get();
        //$patients = Patient::whereNotIn('status', ['Alta', 'Fallecido', 'Hospitalizado UCI (Ventilador)'])->orWhereNull('status')->orderBy('name')->get();
        //$patients = Patient::whereIn('status', ['Ambulatorio','Otra Institución','null',])->orderBy('name')->get();
        //$patients = Patient::whereIn('status', ['Ambulatorio','Otra Institución','null'])->orderBy('name')->get();
        //$patients = Patient::WhereNull('status')->orWhereIn('status', ['Ambulatorio','Otra Institución','Hospitalizado Básico'])->orderBy('name')->get();
        //$patients = Patient::WhereNull('status')->orWhereIn('status', ['Ambulatorio','Otra Institución'])->orderBy('name')->get();
        //$patients = Patient::WhereNull('status')->orderBy('name')->get();
        return view('sanitary_residences.bookings.create', compact('patient','request'));
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
            $booking->patient->status = 'Residencia Sanitaria';
            $booking->patient->save();
            $booking->save();
            session()->flash('success', 'Booking creado Exitosamente');
        }
        else
        {
          $booking = Booking::find($request->booking_id);
          $booking->patient->status = $request->status;
          $booking->patient->save();
          $booking->fill($request->All());
          $booking->save();
          session()->flash('success', 'Paciente dado de Alta Exitosamente');
        }
        return view('sanitary_residences.home');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SanitaryResidence\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function show(Booking $booking)
    {
        //$patients = Patient::orderBy('name')->get();
        $patients = Patient::where('status', 'Residencia Sanitaria')->orderBy('name')->get();
        $rooms = Room::All();
        return view('sanitary_residences.bookings.show', compact('booking', 'patients', 'rooms'));
    }


    public function showRelease(Booking $booking)
    {
        $patients = Patient::orderBy('name')->get();
        $rooms = Room::All();
        return view('sanitary_residences.bookings.showrelease', compact('booking', 'patients', 'rooms'));
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
        // $logPatient = new Log();
        // $logPatient->old = clone $booking;

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

        $booking->delete();
        session()->flash('success', 'Booking eliminado exitosamente');
        //return redirect()->route('sanitary_residences.bookings');
        return view('sanitary_residences.home');
    }

    public function excel(Booking $booking)
    {

        return view('sanitary_residences.bookings.excel.excel', compact('booking'));
    }

    public function excelall()
    {
        $bookings = Booking::where('status','Residencia Sanitaria')
                    ->whereHas('patient', function ($q) {
                        $q->where('status','Residencia Sanitaria');
                    })->get();
        $residences = Residence::all();
        return view('sanitary_residences.bookings.excel.excelall', compact('residences','bookings'));
    }


    public function excelvitalsign()
    {   $bookings = Booking::where('status','Residencia Sanitaria')
        ->whereHas('patient', function ($q) {
            $q->where('status','Residencia Sanitaria');
        })->get();
        $vitalsigns = VitalSign::orderBy('booking_id','ASC')->orderBy('patient_id','ASC')->orderBy('created_at', 'DESC')->get();

        return view('sanitary_residences.bookings.excel.excelvitalsign',compact('bookings','vitalsigns'));
    }

    public function bookingByDate(Request $request){

        if($from = $request->has('from')){
            $from = $request->get('from'). ' 00:00:00';
            $to = $request->get('to'). ' 23:59:59';
        }else{
            $from = Carbon::yesterday();
            $to = Carbon::now();
        }

         $bookings = Booking::whereBetween('from', [$from, $to])
         ->whereNull('deleted_at')
         ->whereHas('room', function ($q) {
            $q->whereNull('deleted_at');
        })
        ->orderBy('from')->get();

        $residences = Residence::withTrashed()->get();



        return view('sanitary_residences.bookings.excel.excelbydate', compact('residences','bookings', 'from', 'to'));
    }





}
