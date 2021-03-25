<?php

namespace App\Http\Controllers;

use App\SanitaryResidence\Booking;
use App\SanitaryResidence\Residence;
use App\SanitaryResidence\Room;
use App\SanitaryResidence\ResidenceUser;
use Illuminate\Http\Request;
use App\User;
//use App\Log;

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

        session()->flash('success', 'Se Asigno al Usuario a la Residencia Sanitaria');
        return redirect()->back();
    }


    public function usersDestroy(ResidenceUser $residenceUser)
    {

        $residenceUser->delete();


        session()->flash('success', 'Usuario Eliminado exitosamente');
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
        // $log = new Log();
        // $log->old = clone $residence;
        // $log->new = $residence->setAttribute('patient','delete');
        // $log->save();

        $residence->delete();

        session()->flash('success', 'Residencia Sanitaria Eliminada exitosamente');
        return redirect()->route('sanitary_residences.residences.index');
    }


    public function map(Residence $residence)
    {
        $dataArray = array();
        $residences = Residence::All();



        foreach ($residences as $residence){
            $counterTotalRoomsByResidence = $residence->rooms()->count();
            $counterPatientsByResidence = 0;
            $counterOccupiedRoomsByResidence = 0;
            $singlecounter = 0;
            $doublecounter = 0;
            $totalsinglebyresidence = $residence->rooms->sum('single');
            $totaldoublebyresidence = $residence->rooms->sum('double');

            $rooms = $residence->rooms;
            foreach ($rooms as $room){                
                $bookings = $room->bookings();                

                $counterPatientsByRoom = $bookings->where('status', 'Residencia Sanitaria')->whereNull('real_to')
                                        ->whereHas('patient', function($q){
                                            $q->where('status', 'Residencia Sanitaria');
                                        })->get()->count();
                

                $counterPatientsByResidence = $counterPatientsByResidence + $counterPatientsByRoom;

                if($counterPatientsByRoom > 0){
                    $counterOccupiedRoomsByResidence = $counterOccupiedRoomsByResidence + 1;
                }

                if($counterPatientsByRoom == 0){
                    $singlecounter = $room->single + $singlecounter;
                    $doublecounter = $room->double + $doublecounter;
                }

            }

            array_push(
                $dataArray, array(
                    'latitude' => $residence->latitude,
                    'longitude' => $residence->longitude,
                    'residenceName' => $residence->name,
                    'totalRooms' => $counterTotalRoomsByResidence,
                    'occupiedRooms' => $counterOccupiedRoomsByResidence,
                    'patients' => $counterPatientsByResidence,
                    'availableRooms' => $counterTotalRoomsByResidence - $counterOccupiedRoomsByResidence,
                    'single' => $singlecounter,
                    'double' => $doublecounter,
                    'totalsinglebyresidence'=> $totalsinglebyresidence,
                    'totaldoublebyresidence'=> $totaldoublebyresidence
                    )
            );

        }

        $totalRooms = 0;
        $totalOccupiedRooms = 0;
        $totalPatients = 0;
        $totalAvailableRooms = 0;
        $totalSingle = 0;
        $totalDouble = 0;
        $sumSingle = 0;
        $sumDouble = 0;

        foreach ($dataArray as $residence){
            
            $totalRooms = $totalRooms + $residence['totalRooms'];
            $totalOccupiedRooms = $totalOccupiedRooms + $residence['occupiedRooms'];
            $totalPatients = $totalPatients + $residence['patients'];
            $totalAvailableRooms = $totalAvailableRooms + $residence['availableRooms'];
            $totalSingle = $totalSingle + $residence['single'];
            $totalDouble = $totalDouble + $residence['double'];
            $sumSingle = $sumSingle + $residence['totalsinglebyresidence'];
            $sumDouble = $sumDouble + $residence['totaldoublebyresidence'];

        }

        
        

        return view('sanitary_residences.residences.map', compact('residences', 'dataArray'));
    }


    

    /**
     * Reporte de estado de residencias
     */
    public function statusReport(){
        $dataArray = array();
        $residences = Residence::all();
        foreach ($residences as $residence){
            $counterTotalRoomsByResidence = $residence->rooms()->count();
            $counterPatientsByResidence = 0;
            $counterOccupiedRoomsByResidence = 0;
            $singlecounter = 0;
            $doublecounter = 0;
            $totalsinglebyresidence = $residence->rooms->sum('single');
            $totaldoublebyresidence = $residence->rooms->sum('double');

            $rooms = $residence->rooms;
            foreach ($rooms as $room){                
                $bookings = $room->bookings();                

                $counterPatientsByRoom = $bookings->where('status', 'Residencia Sanitaria')->whereNull('real_to')
                                        ->whereHas('patient', function($q){
                                            $q->where('status', 'Residencia Sanitaria');
                                        })->get()->count();
                

                $counterPatientsByResidence = $counterPatientsByResidence + $counterPatientsByRoom;

                if($counterPatientsByRoom > 0){
                    $counterOccupiedRoomsByResidence = $counterOccupiedRoomsByResidence + 1;
                }

                if($counterPatientsByRoom == 0){
                    $singlecounter = $room->single + $singlecounter;
                    $doublecounter = $room->double + $doublecounter;
                }

            }

            array_push(
                $dataArray, array(
                    'residenceName' => $residence->name,
                    'totalRooms' => $counterTotalRoomsByResidence,
                    'occupiedRooms' => $counterOccupiedRoomsByResidence,
                    'patients' => $counterPatientsByResidence,
                    'availableRooms' => $counterTotalRoomsByResidence - $counterOccupiedRoomsByResidence,
                    'single' => $singlecounter,
                    'double' => $doublecounter,
                    'totalsinglebyresidence'=> $totalsinglebyresidence,
                    'totaldoublebyresidence'=> $totaldoublebyresidence
                    )
            );

        }

        $totalRooms = 0;
        $totalOccupiedRooms = 0;
        $totalPatients = 0;
        $totalAvailableRooms = 0;
        $totalSingle = 0;
        $totalDouble = 0;
        $sumSingle = 0;
        $sumDouble = 0;

        foreach ($dataArray as $residence){
            $totalRooms = $totalRooms + $residence['totalRooms'];
            $totalOccupiedRooms = $totalOccupiedRooms + $residence['occupiedRooms'];
            $totalPatients = $totalPatients + $residence['patients'];
            $totalAvailableRooms = $totalAvailableRooms + $residence['availableRooms'];
            $totalSingle = $totalSingle + $residence['single'];
            $totalDouble = $totalDouble + $residence['double'];
            $sumSingle = $sumSingle + $residence['totalsinglebyresidence'];
            $sumDouble = $sumDouble + $residence['totaldoublebyresidence'];

        }

        array_push(
            $dataArray, array(
                'residenceName' => 'Total',
                'totalRooms' => $totalRooms,
                'occupiedRooms' => $totalOccupiedRooms,
                'patients' => $totalPatients,
                'availableRooms' => $totalAvailableRooms,
                'single' => $totalSingle,
                'double' => $totalDouble,
                'sumsingle' => $sumSingle,
                'sumdouble' => $sumDouble
                )
        );

        return view('sanitary_residences.residences.statusReport', compact('dataArray'));

    }
}
