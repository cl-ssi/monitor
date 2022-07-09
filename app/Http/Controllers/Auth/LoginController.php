<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\LogSession;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $user = User::whereEmail($request->email)->first();

        if($user->can('Redirection: https://esmeralda.saludtarapaca.org/')) {
            session()->flash('info', 
                'Estimado usuario.<br> Desde ahora deberá ingresar al esmeralda a través de la siguiente dirección: <b>https://esmeralda.saludtarapaca.org</b> <br>Muchas gracias.');
            return redirect()->back();
        }
        if($user && $user->active) {
            if(Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $this->authenticated();
                return redirect()->intended('home');
            } else {
                session()->flash('danger', 'El correo y/o la clave están erradas.');
                return redirect()->back();
            }
        }  else {
            $this->incrementLoginAttempts($request);

            session()->flash('danger', 'La cuenta de usuario no existe o no está activa.');
            return redirect()->back();
        }

        $this->incrementLoginAttempts($request);
        return $this->sendFailedLoginResponse($request);
    }

    /**
     * The user has been authenticated.
     *
     * @return void
     */
    public function authenticated()
    {
        LogSession::create([
            'user_id' => Auth::id(),
            'app_name' => env('APP_NAME'),
            'ip' => request()->getClientIp(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
