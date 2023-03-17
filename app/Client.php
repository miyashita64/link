<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use App\User;
use App\Child;
use App\Facilitie;
use App\Diarie;

class Client extends Model
{
    /**
     * 児童登録
     */
    public static function regist($name, $birthday, $benefic_num, $school_name, $facilitie){
        $client = new Client;
        $now = Date("Y-m-d H:i:s");
        $client->name = $name;
        $client->birthday = $birthday;
        $client->benefic_num = $benefic_num;
        $client->school_name = $school_name;
        $client->facilitie_id = $facilitie->id;
        $client->icon_path = "../img/logo/account.png";
        $client->active = true;
        $client->created_at = $now;
        $client->updated_at = $now;
        $client->save();

        return $client;
    }

    /**
     * カラムのリストを返す
     */
    public static function getKeys(){
        return Schema::getColumnListing((new Client)->getTable());
    }

    /**
     * 対応するChild(子供)を返す
     */
    public function getChild(){
        return Child::find($this->child_id);
    }

    /**
     * 親を返す
     */
    public function getParent(){
        $child = $this->getChild();
        $parent = isset($child->parent_id)? User::find($child->parent_id) : null;
        return  (isset($parent))? $parent : null;
    }

    /**
     * 施設を返す
     */
    public function getFacilitie(){
        return Facilitie::find($this->facilitie_id);
    }

    /**
     * 指定された日の記録を返す
     */
    public function getDiarie($date){
        $diarie = Diarie::where("date", $date)->where("client_id", $this->id)->first();
        if(isset($diarie)){
            $diarie["items"] = DiarieItem::where('diarie_id', $diarie->id)->orderBy('time','asc')->get();
        }
        return $diarie;
    }

    /**
     * 自身の記録を返す
     */
    public function getDiaries(){
        $diaries = Diarie::where("client_id", $this->id)->get();
        if(isset($diaries)){
            $diaries["items"] = DiarieItem::where('diarie_id', $diarie->id)->orderBy('time','asc')->get();
        }
        return $diaries;
    }

    /**
     * 指定された日の活動写真を返す
     */
    public function getActiveImages($date){
        $diarie = $this->getDiarie($date);
        return (isset($diarie))? ActiveImage::where('diarie_id', $diarie->id)->get() : [];
    }

}
