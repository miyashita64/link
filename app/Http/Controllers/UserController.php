<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request){
        $items = User::all();
        return view('user.index', ['items' => $items]);
    }

    public function display(Request $request){
        $datas = [];
        foreach($request->request as $key => $value){
            $datas[$key] = $value;
        }
        return $datas;
    }
}
