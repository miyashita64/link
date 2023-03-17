<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;

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
        $user = User::find(Auth::id());
        if($user["role"] == config('const.Roles.TEACHER')){
            return redirect('/teacher/home');
        }else if($user["role"] == config('const.Roles.PARENT')){
            return redirect('/parent/home');
        }else if($user["role"] == config('const.Roles.WORKER')){
            return redirect('/worker/home');
        }else if($user["role"] <= config('const.Roles.ADMIN')){
            return redirect('/link-admin/home');
        }else{
            return view('home');
        }
    }
}
