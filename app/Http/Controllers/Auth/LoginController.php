<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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

    public function redirectTo(){
        $role = Auth::user()->role;
        switch($role){
            case 'admin':
                return '/admin';
                break;
            case 'student':
                return '/projects';
                break;
            case 'professor':
                return '/projects';
                break;
            default:
                return '/projects';
                break;
        }
        

    }




    protected function authenticated(Request $request, $user)
    {
        return redirect('/login');
    }

    protected function authenticatedAdmin(Request $request, $user){
        return redirect('/admin');
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
