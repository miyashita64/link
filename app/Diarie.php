<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Auth;
use DateTime;
use App\Facilitie;
use App\DiarieItem;
use App\ActiveImage;
use App\Client;

class Diarie extends Model
{
    /**
     * サービス登録
     */
    public static function regist($client, $date){
        $diarie = new Diarie;
        $now = Date("Y-m-d H:i:s");
        $diarie->client_id = $client->id;
        $diarie->writer_id = Auth::id();
        $diarie->date = $date;
        $diarie->pick_driver_id = -1;
        $diarie->active = true;
        $diarie->created_at = $now;
        $diarie->updated_at = $now;
        $diarie->save();

        return $diarie;
    }

    public static function getKeys(){
        return Schema::getColumnListing((new Diarie)->getTable());
    }

    /**
     * 記録を作成した作成施設を取得する
     */
    public function getFacilitie(){
        $client = Client::find($this->client_id);
        return Facilitie::find($client->facilitie_id);
    }

    /**
     * 付属する活動記録を取得する
     */
    public function getDiarieItems(){
        return DiarieItem::where("diarie_id", $this->id)->get();
    }

    /**
     * 現在時刻より前の記録を取得する
     */
    public function getAlreadyDiarieItems(){
        $today = new DateTime();
        $date = new DateTime($this->date);
        $diff_days = $today->diff($date)->format("%a");
        if($diff_days == 0){ // 当日
            $now = Date("H:i:s");
            return DiarieItem::where("diarie_id", $this->id)->where("parent_hidden", false)->where("time", '<=', $now)->orderBy('time','asc')->get();
        }else{
            return $this->getDiarieItems();
        }
    }

    /**
     * 付属する活動写真を取得する
     */
    public function getActiveImage(){
        return ActiveImage::where("diarie_id", $this->id)->get();
    }

    /**
     * 連絡帳の時間更新
     */
    public function updateTime(){
        // サービス記録の更新日時を更新
        $now = Date("Y-m-d H:i:s");
        $this->updated_at = $now;
        // サービス記録を更新
        $this->save();

        return $this;
    }

    /**
     * サービス記録登録・更新（コメント・共有情報）
     */
    public function updateDocument($request){
        // 更新項目を配列化
        $items = [
            "in_time","out_time","private_msg","hidden_msg","service_type",
            "defication","mealtion","hydration","medication","content"
        ];
        // 各更新項目をリクエストからサービス記録へ代入
        foreach($items as $item){
            if(isset($request->$item)){
                $this->$item = $request->$item;
            }else if(in_array($item, ["private_msg","hidden_msg","content"])){
                $this->$item = "";
            }
        }
        // 更新時間を更新
        $now = Date("Y-m-d H:i:s");
        $this->updated_at = $now;
        // サービス記録を更新
        $this->save();

        return $this;
    }

    /**
     * サービス管理登録・更新（管理画面）
     */
    public function updateServiceDocument($request){
        // 更新項目を配列化
        $items = [
            "client_id", "in_time", "out_time", "service_type",
            "pick_depart_time", "pick_addres", "pick_driver_id", "pick_driver_name",
            "drop_depart_time", "drop_addres", "drop_driver_id", "drop_driver_name",
        ];
        // 各更新項目をリクエストからサービス記録へ代入
        foreach($items as $item){
            if(isset($request->$item)) $this->$item = $request->$item;
        }
        // 更新時間を更新
        $now = Date("Y-m-d H:i:s");
        $this->updated_at = $now;
        // サービス記録を更新
        $this->save();

        return $this;
    }

    /**
     * 書類基本情報更新
     */
    public function updateDiarieInfo($request){
        // 更新項目を配列化
        $items = [
            "in_time", "out_time", "service_type",
            "pick_depart_time", "pick_arrive_time",
            "drop_depart_time", "drop_arrive_time",
            "content"
        ];
        // 各更新項目をリクエストからサービス記録へ代入
        foreach($items as $item){
            if(isset($request->$item)) $this->$item = $request->$item;
        }
        // 更新時間を更新
        $now = Date("Y-m-d H:i:s");
        $this->updated_at = $now;
        // サービス記録を更新
        $this->save();

        return $this;
    }

    /**
     * 送迎記録更新
     */
    public function updateTransfer($time, $facilitie, $date){
        foreach($time as $key => $t){
            if($key=="drop_depart_time" || $key=="drop_arrive_time" || $key=="pick_depart_time" || $key=="pick_arrive_time"){
                $this[$key] = $t;
            }
        }
        $this->save();

        return $this;
    }

    /**
     *　送迎サイン画像アップロード
     */
    public function setSignImage($facilitie, $date, $client, $image){
        $signImg = SignImage::regist();
        // 画像登録準備
        $tmp = "img/user_img/signs/";
        $path = public_path($tmp);
        $fileName = $facilitie->id."_".$date."_".$client->id."_".$signImg->id.".png";

        // 自作
        $rate = 1;
        $col = imagecolorallocate($image, 255, 255, 255);
        for($i=0; $i<imagesy($image)/$rate; $i++){
            for($j=0; $j<imagesx($image)/$rate; $j++){
                $y = $i * $rate;
                $x = $j * $rate;
                $rgb = imagecolorat($image, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >>  8) & 0xFF;
                $b = $rgb & 0xFF;
                if($r+$g+$b < 10){
                    imagefilledrectangle($image, $x, $y, $x+$rate, $y+$rate, $col);
                }
            }
        }

        // アップロードされた活動写真を保存
        imagepng($image, $path.$fileName, 0, -1);
        imagedestroy($image);
        // 画像へのパスを登録
        $signImg->path = "../".$tmp.$fileName;
        // サイン画像を登録
        $signImg->save();
        // Diarieとの紐づけ
        $this->sign_id = $signImg["id"];
        // サイン時の時間を、送りの到着時間とする
        if(!($this->drop_arrive_time)){
            $this->drop_arrive_time = (new DateTime('now'))->format("H:i:00");
        }
        $this->save();
        
        return $this;
    }
}
