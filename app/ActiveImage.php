<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class ActiveImage extends Model
{
    /**
     * 画像登録
     */
    public static function regist($diarie){
        $now = Date("Y-m-d H:i:s");
        $actImg = new ActiveImage;
        $actImg->diarie_id = $diarie->id;
        $actImg->writer_id = Auth::id();
        $actImg->active = true;
        $actImg->created_at = $now;
        $actImg->updated_at = $now;
        $actImg->path = "../";
        $actImg->save();

        return $actImg;
    }
}
