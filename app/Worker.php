<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DateTime;
use App\User;
use App\Facilitie;
use App\Career;

class Worker extends Model
{
    /**
     * 職員登録
     */
    public static function regist($facilitie, $user, $name, $permit){
        $worker = new Worker;
        $now = Date("Y-m-d H:i:s");
        $worker->name = $name;
        $worker->user_id = (is_null($user))? null : $user->id;
        $worker->facilitie_id = $facilitie->id;
        $worker->permit = $permit;
        $worker->created_at = $now;
        $worker->updated_at = $now;
        $worker->save();

        return $worker;
    }

    /**
     * 施設名を返す
     * @return string
     */
    public function getFacilitieName(){
        return Facilitie::find($this->facilitie_id)->name;
    }

    /**
     * Userを返す
     */
    public function getUser(){
        return User::find($this->user_id);
    }

    /**
     * 名前を返す
     */
    public function getName(){
        $user = $this->getUser();
        return isset($user)? $user->name : $this->name;
    }

    /**
     * 資格・経歴を返す
     */
    public function getCareers(){
        $careers = Career::where("worker_id", $this->id)->get();
        if(!isset($careers)) return [];
        foreach($careers as $career){
            $career->get_date = explode(" ", $career->get_date)[0];
        }
        return $careers;
    }

    /**
     * 雇用されている全てのfacilitie(施設)を取得する
     * @return Facilitie[]
     */
    public function getEmployedFacilities(){
        $employments = Worker::where("user_id", $this->user_id)->where("active", true)->get();
        return Facilitie::find($employments->pluck("facilitie_id"));
    }

    /**
     * 指定されたidを持つfacilitie(施設)を取得する
     * @return Facilitie | null
     */
    public function getEmployedFacilitie($facilitie_id){
        $facilities = $this->getEmployedFacilities();
        return $facilities->isEmpty()? null
               :($facilities->find($facilitie_id)?
               :($facilities[0]));
    }

    /**
     * 施設職員のメッセージリストを取得する
     */
    public function getWorkerMessageList($facilitie_id){
        $facilitie = $this->getEmployedFacilitie($facilitie_id);
        $message_list = [
            "client" => $facilitie->getClientMessageList(),
            "worker" => $this->getFacilitieWorkerMessageList($facilitie_id),
            "official" => Auth::user()->getOfficialChatList()
        ];
        return $message_list;
    }

    /**
     * 指定された施設における職員間のメッセージリストを取得する
     */
    public function getFacilitieWorkerMessageList($facilitie_id){
        $worker_message_list = [];
        $sort = [];
        $facilitie = $this->getEmployedFacilitie($facilitie_id);
        foreach($facilitie->getWorkers() as $key => $worker){
            $lt = new DateTime("9999-12-31");
            if($worker->user_id==$this->user_id || $worker->user_id==null) continue;
            $ownId = $this->user_id;
            $other_user = User::find($worker->user_id);
            // 選択職員ID > 利用施設ID のメッセージを生成日時でソートし取得
            $messages = Message::where('client_id', null)->where('facilitie_id', $facilitie->id)
            ->where(function($query) use ($ownId){
                $query->orWhere('to_id', $ownId)->orWhere('from_id', $ownId);
            })->where(function($query) use ($other_user){
                $query->orWhere('to_id', $other_user->id)->orWhere('from_id', $other_user->id);
            })->orderBy('created_at','desc')->get();
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
            $worker_message_list[$other_user->id] = [
                "other_id" => $other_user->id,
                "facilitie_id" => $facilitie->id,
                "client_id" => null,
                "icon_path" => $other_user->icon_path,
                "name" => $other_user->name,
                "last_msg" => $last_body,
                "last_dt" => $lt,
                "updated" => $up_time,
                "unread" => $unread
            ];

            // 施設ごとの最終メッセージの生成日時を配列化
            $sort[$key] = $lt? new DateTime($lt->format('Y-m-d H:i:s')) : new DateTime("9999-12-31");
        }

        // チャット情報を、最終メッセージの生成日時でソート
        array_multisort($sort, SORT_DESC, $worker_message_list);

        return $worker_message_list;
    }

    /**
     * 一括登録更新
     */
    public function updateBatchActivity($client_ids, $time, $activity, $comment, $parent_hidden, $date, $request_share_id){
        $share_item_id = null;
        $diarie = null;
        if($request_share_id < 0){
            $exist = Diarie::whereIn("client_id", $client_ids)->where("date", $date)->exists();
            if(!$exist){
                exit();
            }
            $item = DiarieItem::regist($diarie, $time, $activity, $comment, $parent_hidden, $share_item_id);
        }else{
            $item = DiarieItem::find($request_share_id);
            if(!is_null($item)){
                $item->updateItem($diarie, $time, $activity, $comment, $parent_hidden, $share_item_id);
            }else{
                exit();
            }
        }

        if($request_share_id >= 0){
            $diarie_items = DiarieItem::where("share_item_id", $request_share_id)->get(["diarie_id"]);
            $diarie_items = json_decode(json_encode($diarie_items), true);
            $diarie_ids = array_column($diarie_items, 'diarie_id');
            $diaries = Diarie::whereIn("id", $diarie_ids)->get(["client_id"]);
            $diaries = json_decode(json_encode($diaries), true);
            $client_ids = array_column($diaries, 'client_id');
        }

        if($request_share_id < 0){
            $share_item = DiarieItem::latest()->first();
            $share_item_id = $share_item->id;
        }else{
            $share_item_id = $request_share_id;
        }

        foreach ($client_ids as $client) {
            //Diarieの取得
            $diarie = Diarie::where("client_id", $client)->where("date", $date)->first();
            if(isset($diarie)){
                // サービス記録の更新日時を更新
                $diarie->updateTime();
                if($request_share_id < 0){
                    $item = DiarieItem::regist($diarie, $time, $activity, $comment, $parent_hidden, $share_item_id);
                }else{
                    $item = DiarieItem::where("diarie_id", $diarie["id"])->where("share_item_id", $share_item_id)->first();
                    $item->updateItem($diarie, $time, $activity, $comment, $parent_hidden, $share_item_id);
                }
            }
        }

        return $this;
    }

    /**
     * 一括削除
     */
    public function deleteBatchActivity($request_share_id){
        $diarieItems = DiarieItem::where("share_item_id", $request_share_id)->get();
        foreach($diarieItems as $diarieItem){
            if($diarieItem){
                $diarie = Diarie::find($diarieItem->diarie_id);
                if(!is_null($diarie)){
                    // 連絡帳レコードが存在すれば削除
                    $diarieItem->delete();
                }
            }
        }
        $diarieItem_first = DiarieItem::find($request_share_id);
        $diarieItem_first->delete();

        return $this;
    }

    /**
     * 職員の履歴を取得する
     */
    public function getWorkerHistories(){
        $exist = DiarieItem::where('writer_id', $this->user_id)->exists();
        if($exist){
            $histories = DiarieItem::where('writer_id', $this->user_id)->latest()->get();
            $items = array($histories[0]->activity);
            foreach ($histories as $history){
                if(!in_array($history->activity, $items, true)){
                    $items[] = $history->activity;
                }
                if(count($items) == 5) break;
            }
            if(count($items) < 5){
                $itemsList = array('活動', '水分補給', '排泄', '食事', '服薬');
                foreach ($itemsList as $item){
                    if(!in_array($item, $items, true)){
                        $items[] = $item;
                    }
                    if(count($items) == 5) break;
                }
            }
        }else{
            $items = array('活動', '水分補給', '排泄', '食事', '服薬');
        }
        return $items;
    }

    /**
     * 職員削除
     */
    public function delete(){
        $now = Date("Y-m-d H:i:s");
        $this->active = false;
        $this->updated_at = $now;
        $this->save();

        return $this;
    }

    /**
     * 職員復元
     */
    public function restore(){
        $now = Date("Y-m-d H:i:s");
        $this->active = true;
        $this->updated_at = $now;
        $this->save();

        return $this;
    }

    /**
     * 画像アップロード
     */
    public function uploadActiveImage($diarie, $client, $date, $facilitie, $file, $tmp){
        $actImg = ActiveImage::regist($diarie);

        // 画像登録準備
        $path = public_path($tmp);
        $fileName = $actImg->id.".".$file->getClientOriginalExtension();
        // アップロードされた活動写真を保存
        $file->move($path, $fileName);
        // 画像へのパスを登録
        $actImg->path = "../".$tmp.$fileName;
        // 活動写真を登録
        $actImg->save();

        return $actImg;
    }

    /**
     * 一括画像アップロード
     */
    public function uploadBatchActiveImage($client, $date, $facilitie, $path){
        $diarie = Diarie::where("client_id", $client->id)->where("date", $date)->first(["id"]);
        $actImg = ActiveImage::regist($diarie);

        // 画像へのパスを登録
        $actImg->path = $path;
        // 活動写真を登録
        $actImg->save();

        return $actImg;
    }

    /**
     * 利用者承認
     */
    public static function approvalChild($child, $client){
        $now = Date("Y-m-d H:i:s");
        $client->child_id = $child->id;
        $client->updated_at = $now;
        $client->save();

        return $client;
    }

}
