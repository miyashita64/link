<?php

namespace App\Http\Controllers;

use App\User;
use App\Client;
use App\Diarie;
use App\Facilitie;
use App\Message;
use Illuminate\Http\Request;

class DataBaseViewController extends Controller
{
    public function __invoke(){
        $datas = [
            "user" => User::all(),
            "client" => Client::all(),
            "diarie" => Diarie::all(),
            "facilitie" => Facilitie::all(),
            "message" => Message::all()
        ];
        $keys = [
            "user" => User::getKeys(),
            "client" => Client::getKeys(),
            "diarie" => Diarie::getKeys(),
            "facilitie" => Facilitie::getKeys(),
            "message" => Message::getKeys()
        ];
        return view("sample.index",["datas"=>$datas, "keys"=>$keys]);
    }
}
