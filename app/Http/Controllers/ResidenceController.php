<?php

namespace App\Http\Controllers;

use App\SanitaryResidence\Booking;
use App\SanitaryResidence\Residence;
use App\SanitaryResidence\ResidenceUser;
use Illuminate\Http\Request;
use App\User;
use App\Log;

class ResidenceController extends Controller
{
    public function home()
    {
        return view('sanitary_residences.home');
    }

    public function users()
    {
        $residences = Residence::all();
        $users = User::orderBy('name')->get();
        $residenceUsers = ResidenceUser::orderBy('residence_id')->get();
        return view('sanitary_residences.users',compact('residenceUsers','users','residences'));

    }

    public function usersStore(Request $request)
    {
        $residence_user = new ResidenceUser($request->All());
        $residence_user->save();

        return redirect()->back();
    }


    public function usersDestroy(ResidenceUser $residenceUser)
    {

        $residenceUser->delete();


        session()->flash('success', 'Permisos Eliminados exitosamente');
        //return redirect()->route('sanitary_residences.residences.index');
        return redirect()->back();

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $residences = Residence::All();
        return view('sanitary_residences.residences.index', compact('residences'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('sanitary_residences.residences.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $residence = new Residence($request->All());
        $residence->save();

        return redirect()->route('sanitary_residences.residences.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SanitaryResidence\Residence  $residence
     * @return \Illuminate\Http\Response
     */
    public function show(Residence $residence)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SanitaryResidence\Residence  $residence
     * @return \Illuminate\Http\Response
     */
    public function edit(Residence $residence)
    {
        return view('sanitary_residences.residences.edit', compact('residence'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SanitaryResidence\Residence  $residence
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Residence $residence)
    {
        $residence->fill($request->all());
        $residence->save();
        session()->flash('success', 'Se modificó la residencia exitosamente');
        return redirect()->route('sanitary_residences.residences.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SanitaryResidence\Residence  $residence
     * @return \Illuminate\Http\Response
     */
    public function destroy(Residence $residence)
    {
        $log = new Log();
        $log->old = clone $residence;
        $log->new = $residence->setAttribute('patient','delete');
        $log->save();

        $residence->delete();

        session()->flash('success', 'Residencia Sanitaria Eliminada exitosamente');
        return redirect()->route('sanitary_residences.residences.index');
    }

    /**
     * Reporte de residencias
     */
    public function report(){
//        $bookings = Booking::where('status', 'Residencia Sanitaria')->whereNull('real_to')
//                    ->whereHas('patient', function($q){
//                        $q->where('status', 'Residencia Sanitaria');
//                    })->get();

        $dataArray = array();
        $residences = Residence::all();
        foreach ($residences as $residence){
            $counterTotalRoomsByResidence = $residence->rooms()->count();
            $counterPatientsByResidence = 0;
            $counterOccupiedRoomsByResidence = 0;

            $rooms = $residence->rooms;
            foreach ($rooms as $room){
                $bookings = $room->bookings();

                $counterPatientsByRoom = $bookings->where('status', 'Residencia Sanitaria')->whereNull('real_to')->count();
                $counterPatientsByResidence = $counterPatientsByResidence + $counterPatientsByRoom;

                if($counterPatientsByRoom > 0){
                    $counterOccupiedRoomsByResidence = $counterOccupiedRoomsByResidence + 1;
                }

            }

            array_push(
                $dataArray, array(
                    'residenceName' => $residence->name,
                    'totalRooms' => $counterTotalRoomsByResidence,
                    'occupiedRooms' => $counterOccupiedRoomsByResidence,
                    'patients' => $counterPatientsByResidence,
                    'availableRooms' => $counterTotalRoomsByResidence - $counterOccupiedRoomsByResidence)
            );

        }
        dump($dataArray);

    }
}
