<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Hash;
use Session;
use App\Models\User;
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


    public function customLogin(Request $request)
    {


        $credentials = $request->only('UserEmail', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()->route('Dashboard');
        }

        $user = User::where('UserEmail',$request->UserEmail)->first();
        if(!empty($user->UserEmail)){
            return redirect()->back()->withInput($request->only('UserEmail', 'password'))->withErrors([
                'password' => 'Invalid Password!',
            ]);
        }else{
            return redirect()->back()->withInput($request->only('UserEmail', 'password'))->withErrors([
                'UserEmail' => 'Invalid e-mail address!',
            ]);
        }

    }
}
