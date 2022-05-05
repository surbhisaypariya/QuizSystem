<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
    * @return \Illuminate\Contracts\Support\Renderable
    */
    public function index()
    {
        if(Auth::user()->role == 'student')
        {
            Auth::logout();
            Session::flush();
            return redirect('login')->with('error','Access Denied');
        }
        return view('admin.dashboard');
    }
}
