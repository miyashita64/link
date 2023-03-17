<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Career extends Model
{
    /**
     * 経歴登録
     */
    public static function regist($career_name, $get_date, $worker){
        $career = new Career;
        $now = Date("Y-m-d H:i:s");
        $career->name = $career_name;
        $career->get_date = $get_date;
        $career->worker_id = $worker->id;
        $career->created_at = $now;
        $career->updated_at = $now;
        $career->save();

        return $career;
    }

}
