<?php

namespace App\Http\Controllers;

use App\User;
use App\Laboratory;
use App\Establishment;
use App\EstablishmentUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::Latest()->get();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $laboratories = Laboratory::orderBy('name')->get();
        $permissions = Permission::OrderBy('name')->get();

        $env_communes = array_map('trim',explode(",",env('COMUNAS')));
        $establishments = Establishment::whereIn('commune_id',$env_communes)->orderBy('name','ASC')->get();

        return view('users.create', compact('permissions','laboratories', 'establishments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = new User();
        $user->run = $request->input('run');
        $user->dv = $request->input('dv');
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->laboratory_id = $request->input('laboratory_id');
        $user->password = bcrypt($request->input('password'));

        $user->save();

        $user_est = new User($request->All());

        foreach ($user_est->establishment_id as $key => $id) {
            $establishment_user = new EstablishmentUser();

            $establishment_user->establishment_id = $id;
            $establishment_user->user_id = $user->id;
            $establishment_user->save();
        }

        $user->syncPermissions(
            is_array($request->input('permissions')) ? $request->input('permissions') : array()
        );

        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $laboratories = Laboratory::orderBy('name')->get();
        $permissions = Permission::OrderBy('name')->get();

        $env_communes = array_map('trim',explode(",",env('COMUNAS')));
        $establishments = Establishment::whereIn('commune_id',$env_communes)->orderBy('name','ASC')->get();

        $establishments_user = EstablishmentUser::where('user_id', $user->id)->get();

        $establishment_selected = array();
        foreach($establishments_user as $key => $establishment_user){
            $establishment_selected[$key] = $establishment_user->establishment_id;
        }

        return view('users.edit', compact('user','permissions','laboratories', 'establishments', 'establishment_selected', 'establishments_user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        // $user->fill($request->all());
        $user->run = $request->input('run');
        $user->dv = $request->input('dv');
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->laboratory_id = $request->input('laboratory_id');
        //$user->password = bcrypt($request->input('password'));
        $user->save();

        /* ESTABLECIMIENTOS ACTUALES */
        $establishments_user = EstablishmentUser::where('user_id', $user->id)->get();

        $establishment_selected = array();
        foreach($establishments_user as $key => $establishment_user){
            $establishment_selected[$key] = intval($establishment_user->establishment_id);
        }

        /* ------------------------------------------------------------------ */

        /* ESTABLECIMIENTOS EDIT */
        $user_est = new User($request->All());
        $new_establishment_user = array();
        foreach ($user_est->establishment_id as $key => $id) {
            $new_establishment_user[$key] = intval($id);
        }

        /* CONSULTO SI LOS ARRAY SON IGUALES */
        $arraysAreEqual = ($establishment_selected == $new_establishment_user);
        if($arraysAreEqual == false){
            EstablishmentUser::where('user_id', $user->id)->delete();

            foreach ($new_establishment_user as $key => $id) {
                $establishment_user = new EstablishmentUser();

                $establishment_user->establishment_id = $id;
                $establishment_user->user_id = $user->id;
                $establishment_user->save();
            }
        }

        $user->syncPermissions(
            is_array($request->input('permissions')) ? $request->input('permissions') : array()
        );

        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function showPasswordForm()
    {
        return view('users.change_password');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request)
    {
        if(Hash::check($request->input('current_password'), Auth()->user()->password)) {
            Auth()->user()->password = bcrypt($request->input('new_password'));
            Auth()->user()->save();
        }

        // TODO: Mostrar error si la clave antigua no coincide
        return redirect()->route('home');
    }


    /**
     * Show form for restore password.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function passwordRestore(User $user)
    {
        return view('users.restore', compact('user'));
    }

    /**
     * Set random password to user
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function passwordStore(Request $request, User $user)
    {
        if($request->input('password')) {
            $password = $request->input('password');
            $user->password = bcrypt($request->input('password'));
        }
        else {
            $password = substr(str_shuffle(MD5(microtime())), 0, 6);
            $user->password = bcrypt($password);
        }
        $user->save();

        session()->flash('info', 'Password: '. $password);
        return redirect()->back();
    }
}
