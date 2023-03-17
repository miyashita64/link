<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DateTime;
use App\Diarie;

class SignImage extends Model
{
    /**
     * サイン登録
     */
    public static function regist(){
        $signImg = new SignImage;
        $now = Date("Y-m-d H:i:s");
        $signImg->writer_id = Auth::id();
        $signImg->path = "../";
        $signImg->active = true;
        $signImg->created_at = $now;
        $signImg->updated_at = $now;

        $signImg->save();

        return $signImg;
    }

}
