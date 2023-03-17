<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class DiarieItem extends Model
{
    protected $dates = [
        'time',
    ];

    /**
     * 連絡帳新規登録
     */
    public static function regist($diarie, $time, $activity, $comment, $parent_hidden, $share_item_id){
        $now = Date("Y-m-d H:i:s");
        $diarie_item = new DiarieItem;
        $diarie_item->diarie_id = (is_null($diarie))? null : $diarie->id;
        $diarie_item->writer_id = Auth::id();
        $diarie_item->time = $time;
        $diarie_item->activity = $activity;
        $diarie_item->comment = $comment;
        $diarie_item->parent_hidden = ($parent_hidden != null)? true : false;
        $diarie_item->share_item_id = $share_item_id;
        $diarie_item->active = true;
        $diarie_item->created_at = $now;
        $diarie_item->updated_at = $now;
        $diarie_item->save();

        return $diarie_item;
    }

    /**
     * 連絡帳更新
     */
    public function updateItem($diarie, $time, $activity, $comment, $parent_hidden, $share_item_id){
        $now = Date("Y-m-d H:i:s");
        $this->diarie_id = (is_null($diarie))? null : $diarie->id;
        $this->writer_id = Auth::id();
        $this->time = $time;
        $this->activity = $activity;
        $this->comment = $comment;
        $this->parent_hidden = ($parent_hidden != null)? true : false;
        $this->share_item_id = $share_item_id;
        $this->active = true;
        $this->updated_at = $now;
        $this->save();

        return $this;
    }

    /**
     * サービスレコードの時間更新
     */
    public function updateTime($time){
        if($this){
            $diarie = Diarie::find($this->diarie_id);
            if(isset($diarie)){
                // 連絡帳レコードが存在すれば削除
                $this->time = $time;
                $this->save();
            }
        }

        return $this;
    }


}
