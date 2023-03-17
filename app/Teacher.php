<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\School;

class Teacher extends Model
{
    /**
     * 教員の履歴を取得する
     */
    public function getTeacherHistories(){
        $BASE_ITEMS = array('発表','私語','居眠り','集中なし','内職');
        $exist = SchoolItem::where('teacher_id', $this->id)->exists();
        if($exist){
            $histories = SchoolItem::where('teacher_id', $this->id)->latest()->get();
            $items = array($histories[0]->activity);
            foreach ($histories as $history){
                if(!in_array($history->activity, $items, true)){
                    $items[] = $history->activity;
                }
                if(count($items) == 5) break;
            }
            if(count($items) < 5){
                $itemsList = $BASE_ITEMS;
                foreach ($itemsList as $item){
                    if(!in_array($item, $items, true)){
                        $items[] = $item;
                    }
                    if(count($items) == 5) break;
                }
            }
        }else{
            $items = $BASE_ITEMS;
        }
        return $items;
    }
}
