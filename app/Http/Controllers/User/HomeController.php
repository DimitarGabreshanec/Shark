<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        return view('user.home.welcome');
    }

    public function privacy()
    {
        return view('user.home.privacy');
    }

    public function termAndUse()
    {
        return view('user.home.term_use');
    }

    public function sendFirebaseToken (Request $request) { 
        $firebase_token = $request->input('firebase_token');
        Session::put('firebase_token', $firebase_token);  
        return 'Sent firebase token successfully.';
    } 

    
}
