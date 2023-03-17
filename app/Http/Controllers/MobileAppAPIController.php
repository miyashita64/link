<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class MobileAppAPIController extends Controller
{
    // ログイン中のユーザ情報を返す
    public function authUser(Request $request){
        return json_encode(Auth::user());
    }

    // ログイン済みか否かを返す
    public function loggedIn(Request $request){
        return json_encode(["logged_in" => Auth::check()]);
    }
}
