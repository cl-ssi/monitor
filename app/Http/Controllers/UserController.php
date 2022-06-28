<?php

namespace App\Http\Controllers;

use App\User;
use App\Laboratory;
use App\Establishment;
use App\EstablishmentUser;
use App\Dialysis\DialysisCenter;
use App\LogSession;
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
    public function index(Request $request)
    {

        $users = User::orderBy('name', 'asc')->get();

        if ($request) {

            $query = trim($request->get('search'));

            $users = User::where('name', 'LIKE', '%' . $query . '%')
                    ->orderBy('name', 'asc')
                    ->get();

            return view('users.index', ['users' => $users, 'search' => $query]);

        }

        return view('users.index', compact('users', 'request', 'query'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $laboratories = Laboratory::where('external',0)->orderBy('name')->get();
        $permissions = Permission::OrderBy('name')->get();
        //$dialysiscenters = DialysisCenter::OrderBy('name')->get();



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
        $validatedData = $request->validate([
           'run' => ['unique:users']
        ],
        [
            'run.unique' => 'Este rut ya está registrado.'
        ]);

        $user = new User();
        $user->run = $request->input('run');
        $user->dv = $request->input('dv');
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->telephone = $request->input('telephone');
        $user->function = $request->input('function');
        $user->laboratory_id = $request->input('laboratory_id');
        $user->establishment_id = $request->input('my_establishment_id');
        //$user->dialysis_center_id = $request->input('dialysis_center_id');
        //$user->password = bcrypt($request->input('password'));
        if($request->input('password')) {
            $password = $request->input('password');
            $user->password = bcrypt($request->input('password'));
        }
        else {
            $password = substr(str_shuffle(MD5(microtime())), 0, 6);
            $user->password = bcrypt($password);
        }
        // $password = substr(str_shuffle(MD5(microtime())), 0, 6);
        // $user->password = bcrypt($password);

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


        session()->flash('success', 'Estimado Usuari@



        Mediante el presente correo se hace entrega de su clave para el monitor Esmeralda. El link para ingresar es el siguiente



        https://i.saludiquique.cl/monitor/



        en correo deberá digitar al correo que le está llegando este mail y su  contraseña temporal será



        '.$password.'



        Se recomienda cambiar la contraseña a una que sea más fácil de recordar, para eso podrá apretar en la esquina superior derecha en el sistema en la opción" cambiar clave. Y seguir los pasos correspondientes





        Se despide atentamente



        Atte.');

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
        $laboratories = Laboratory::where('external',0)->orderBy('name')->get();
        $permissions = Permission::OrderBy('name')->get();
        //$dialysiscenters = DialysisCenter::OrderBy('name')->get();

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
        $user->establishment_id = $request->input('my_establishment_id');
        //$user->dialysis_center_id = $request->input('dialysis_center_id');
        $user->telephone = $request->input('telephone');
        $user->function = $request->input('function');
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

        session()->flash('success', 'Usuario Actualizado Exitosamente');
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
        $user->delete();
        session()->flash('success', "Se ha eliminado usuario: '$user->name'");
        return redirect()->route('users.index');

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

    /**
     * Last access
     *
     * @return \Illuminate\Http\Response
     */
    public function lastAccess()
    {
        $logSessions = LogSession::query()
            ->latest()
            ->paginate(100);

        return view('users.last-access', compact('logSessions'));
    }

    /**
     * Update the active field of the user
     *
     * @param \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function updateActive(User $user)
    {
        $user->update([
            'active' => !$user->active
        ]);

        $msg = $user->active ? 'activado' : 'desactivado';

        session()->flash('success', "El usuario $user->name fue $msg.");
        return redirect()->route('users.edit', $user);
    }
}
