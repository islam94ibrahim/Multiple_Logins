<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use App\ActivationService;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
        $this->activationService = $activationService;
    }
    
    public function register(Request $request)
    {   
        if($request->type === "1"){
            $validator = $this->OrchestraValidator($request->all());
        }
        if($request->type === "2"){
            $validator = $this->MusicianValidator($request->all());
        }
        

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        if($request->type === "1"){
            
            $user = $this->OrchestraCreate($request->all());
        }
        if($request->type === "2"){
            $user = $this->MusicianCreate($request->all());
        }
        
        // Change All users passwords to be the same
        $SameEmail=User::where('email',$request->email)->get();
        if(count($SameEmail) >1){
            foreach ($SameEmail as $eachUser) {
                $CurrentUser=User::where('id',$eachUser->id)->first();
                $CurrentUser->password = bcrypt($request->password);
                $CurrentUser->remember_token = Str::random(60);
                $CurrentUser->save();
            }
        }        

        $this->activationService->sendActivationMail($user);

        return redirect('/login')->with('status', 'We sent you an activation code. Check your email.');
    }


    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function OrchestraValidator(array $data)
    {
        return Validator::make($data, [
            'firstname' => 'required|max:255',
            'surname' => 'required|max:255',
            'orchestra_name' => 'required|max:255',
            'email' => 'required|email|max:255|unique_with:users,type',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    protected function MusicianValidator(array $data)
    {
        return Validator::make($data, [
            'firstname' => 'required|max:255',
            'surname' => 'required|max:255',
            'email' => 'required|email|max:255|unique_with:users,type',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function OrchestraCreate(array $data)
    {
        

        return User::create([
            'firstname' => $data['firstname'],
            'surname' => $data['surname'],
            'email' => $data['email'],
            'gender' => $data['gender'],
            'type' => (int)$data['type'],
            'orchestra_name' => $data['orchestra_name'],
            'password' => bcrypt($data['password']),
        ]);

        
    }
    protected function MusicianCreate(array $data)
    {

        return User::create([
            'firstname' => $data['firstname'],
            'surname' => $data['surname'],
            'email' => $data['email'],
            'gender' => $data['gender'],
            'type' => (int)$data['type'],
            'password' => bcrypt($data['password']),
        ]);

        
    }
}
