<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DateTime;
use App\User;
use App\Client;
use App\Diarie;
use App\Facilitie;
use App\Message;

class Child extends Model
{
    /**
     * 対応する利用者(Client)を返す
     */
    public function getClients(){
        $clients = Client::where("child_id", $this->id)->get();
        foreach($clients as $client){
            $client["facilities"] = Facilitie::find($clients->pluck("facilitie_id"));
        }
        return $clients;
    }

    /**
     * 指定された利用者を返す
     */
    public function getClient($facilitie_id){
        $clients = $this->getClients();
        return $clients->isEmpty() || $facilitie_id==null? ["id"=>null]
               :($clients->where("facilitie_id", $facilitie_id)->first()?
               :($clients[0]));
    }

    /**
     * 指定された日の記録を返す
     */
    public function getDiaries($date){
        $clients = $this->getClients();
        $diaries = [];
        foreach($clients as $key => $client){
            $diaries[$key] = Diarie::where("client_id", $client->id)->where("date", $date)->first();
        }
        return $diaries;
    }

    /**
     * 本児童に係わるチャットリストを返す
     */
    public function getMessageList(){
        // 関連施設がない場合、空のメッセージリストを返す
        $facilitie_message_list = [];
        // ソート用配列宣言
        $sort = [];
        // 施設ごとにメッセージ内容をまとめる
        foreach($this->getClients() as $key => $client){
            $facilitie = $client->getFacilitie();
            // 利用者ID > 利用施設ID のメッセージを生成日時でソートし取得
            $messages = Message::where('client_id', $client->id)->where('facilitie_id', $facilitie->id)->orderBy('created_at','desc')->get();
            // リスト上の最終メッセージ情報の格納
            if(count($messages)>0){
                // メッセージ数が1以上なら、最終メッセージからの時間を算出
                // 最終メッセージ取得
                $last = $messages->first();
                $lt = $last["created_at"];
                $d = ((new DateTime('now'))->diff(new DateTime($lt->format('Y-m-d'))));
                $up_time = (int)$d->format('%y')>0? $d->format('%y')."年前"
                         :((int)$d->format('%m')>0? $d->format('%m')."か月前"
                         :((int)$d->format('%d')>0? $d->format('%d')."日前"
                         :($lt->format('H:i'))));
                $last_body = $last["body"];
            }else{
                // メッセージのやり取りがなければ、専用メッセージを生成
                $up_time = "";
                $last_body = "施設とメッセージのやり取りができます";
            }

            // 未読メッセージをカウント
            $unread = 0;
            foreach($messages as $message){
                if($message["from_id"]!=Auth::id()){
                    if(!$message["readed"]) $unread++;
                }else{
                    break;
                }
            }

            // 関連情報を施設ごとにまとめる
            $facilitie_message_list[$facilitie->id] = [
                "other_id" => $facilitie->admin_id,
                "facilitie_id" => $facilitie->id,
                "client_id" => $client->id,
                "icon_path" => $facilitie->icon_path,
                "name" => $facilitie->name,
                "last_msg" => $last_body,
                "updated" => $up_time,
                "unread" => $unread
            ];

            // 施設ごとの最終メッセージの生成日時を配列化
            $sort[$key] = isset($lt)? new DateTime($lt->format('Y-m-d H:i:s')) : new DateTime("9999-12-31");
        }

        // チャット情報を、最終メッセージの生成日時でソート
        if(isset($message_list["facilitie"])){
            array_multisort($sort, SORT_DESC, $facilitie_message_list);
        }

        return $facilitie_message_list;
    }

    /**
     * 本児童に関わるチャットを取得する
     */
    public function getMessages($facilitie_id){
        $client = $this->getClient($facilitie_id);
        $facilitie = $client->getFacilitie();

        // 選択利用者 > 選択施設 のメッセージを生成日時でソートし取得
        $message_values = Message::where('client_id', $client->id)->where('facilitie_id', $facilitie->id)->orderBy("created_at","asc")->get();
        // 選択中の施設の管理者を取得
        $admin = User::find($facilitie->admin_id);
        // チャットの基本情報を取得
        $messages = [
            "own_id" => Auth::id(),
            "own_icon" => $client->icon_path,
            "other_id" => $admin["id"],
            "other_icon" => $facilitie->icon_path,
            "other_name" => $facilitie->name,
            "facilitie_id" => $facilitie->id,
            "client_id" => $client->id,
            "chats" => []
        ];
        // メッセージ数が0以下の場合、関数を終了
        if(count($message_values)<=0) return $messages;

        $i = 0;
        $date = '0';
        foreach($message_values as $message){
            // メッセージを日付ごとにまとめる
            if($date!=$message["created_at"]->format('Y-m-d')){
                $date = $message["created_at"]->format('Y-m-d');
                $messages["chats"][++$i]["date"] = $date;
                $j = 0;
            }
            // 既読更新
            if($message["to_id"]==Auth::id() && !$message["readed"]){
                $message["readed"] = true;
                $message->save();
            }

            // メッセージ内容を格納
            $messages["chats"][$i]["messages"][$j++] = [
                "time" => $message["created_at"]->format('H:i'),
                "send_fg" => $message["from_id"]==Auth::id(),
                "text" => $message["body"],
                "readed" => $message["readed"]
            ];
        }
        return $messages;
    }

    /**
     * 児童登録
     */
    public static function registChild($request){
        // 各要素を代入
        $now = Date("Y-m-d H:i:s");
        $child = new Child;
        $child->name = $request->name;
        $child->parent_id = Auth::id();
        $child->icon_path = "../img/logo/account.png";
        $child->created_at = $now;
        $child->updated_at = $now;
        // 利用者登録
        $child->save();

        return $child;
    }

    /**
     * 児童プロフィール更新
     */
    public function updateChild($request){
        if($this->parent_id==Auth::id()){
            // 各項目の値を更新
            $this->name = $request->name;
            // プロフィール画像がアップロードされた場合
            if($file = $request->icon_img){
                $path = public_path("img/user_img/clients/");
                $fileName = $this->id.".".$file->getClientOriginalExtension();
                // アップロードされたプロフィール画像を保存
                $file->move($path, $fileName);
                // 画像へのパスを登録
                $this->icon_path = "../img/user_img/clients/".$fileName;
            }
            $now = Date("Y-m-d H:i:s");
            $this->updated_at = $now;
            // 利用者情報を更新
            $this->save();
        }
        return $this;
    }
}
