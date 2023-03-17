<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Auth;

class Message extends Model
{
    protected $dates = ['created_at', 'updated_at'];

    public static function getKeys(){
        return Schema::getColumnListing((new Message)->getTable());
    }

    /**
     * メッセージ登録
     */
    public static function regist($to_id, $client, $facilitie, $body){
        $now = Date("Y-m-d H:i:s");
        $message = new Message;
        $message->from_id = Auth::id();
        $message->to_id = $to_id;
        $message->client_id = $client->id;
        $message->facilitie_id = $facilitie->id;
        $message->body = $body;
        $message->readed = false;
        $message->active = true;
        $message->created_at = $now;
        $message->updated_at = $now;
        $message->save();
        return $message;
    }
}
