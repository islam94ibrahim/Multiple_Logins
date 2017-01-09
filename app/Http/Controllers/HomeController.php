<?php

namespace App\Http\Controllers;

use Illuminate\Mail\Mailer;
use Illuminate\Mail\Message;
use Illuminate\Http\Request;
use App\User;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        
        $users = User::where([['type','=', 2],['activated', '=', true]])->get();
        return view('home',compact('users'));
    }

    public function AddMembers(Request $request){
        $id=explode(":",$request->member)[0];
        $user=User::where('id', $id)->first();

        $FoundUser=User::where([
            ['email', '=', $user->email],
            ['type', '=', (int)3],
        ])->first();
        
        if($FoundUser){
            if($FoundUser->orchestra_name == \Auth::User()->orchestra_name){
                return redirect()->back()->with('Error', "You cant Add this member, He is already in your Orchestra");  
            }
          return redirect()->back()->with('Error', "You cant Add this member, He is already in other Orchestra");  
        }

        $user->type = 3;
        $user->orchestra_name = $request->orchestra_name;
        $user->save();
        //Session::flash('message', "Member has been added successfully");
        return redirect()->back()->with('message', "Member has been added successfully"); 
        
    }
}
