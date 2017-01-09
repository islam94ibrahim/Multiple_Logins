<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\ActivationService;
use Illuminate\Http\Request;
use App\User;
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
    protected $redirectTo = '/home';
    protected $activationService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ActivationService $activationService)
    {
        $this->middleware('guest', ['except' => 'logout']);
        $this->activationService = $activationService;
    }

    public function activateUser($token)
    {
        if ($user = $this->activationService->activateUser($token)) {
            auth()->login($user);
            return redirect($this->redirectPath());
        }
        abort(404);
    }

    public function MyLogin(Request $request){

        $IsUser=User::where([
            ['email', '=', $request->email],
            ['type', '=', (int)$request->type],
        ])->first();

        if($IsUser){
            if(\Hash::check($request->password, $IsUser->password)){
                if($IsUser->activated == true){
                    if (\Auth::attempt(['email' => $request->email, 'password' => $request->password, 'type' => (int)$request->type])) {
                        return redirect('/home');
                        }
                }
                else{
                    return back()->with('warning', 'You need to confirm your account. We have sent you an activation code, please check your email.');
                }
            }
        }

        return back()->with('warning', 'These credentials do not match our records.'); 
             
    }
}
