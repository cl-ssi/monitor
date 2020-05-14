<?php

namespace App\Http\Controllers;

use App\SanitaryResidence\Residence;
use App\SanitaryResidence\ResidenceUser;
use Illuminate\Http\Request;
use App\User;

class ResidenceController extends Controller
{
    public function home()
    {
        return view('sanitary_residences.home');
    }

    public function users()
    {
        $users = User::all();
        $residences = Residence::all();
        $users_with_residences = User::has('residences')->get();
        return view('sanitary_residences.users',compact('users','residences','users_with_residences'));
    }

    public function usersStore(Request $request)
    {
        $residence_user = new ResidenceUser($request->All());
        $residence_user->save();

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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SanitaryResidence\Residence  $residence
     * @return \Illuminate\Http\Response
     */
    public function destroy(Residence $residence)
    {
        //
    }
}
