<?php

namespace App\Http\Controllers\Auth;
  
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Auth;
   
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
     * Write code on Method
     *
     * @return response()
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
     
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            if (auth()->user()->hasRole('admin')) {
                return redirect(route('admin.dashboard'));
            }elseif(auth()->user()->role==4){
                return redirect(route('admin.dashboard'));
            }
            return redirect()->route('dashboard');
        }
    
        return redirect("login")->withSuccess('Oppes! You have entered invalid credentials');
    }
}
