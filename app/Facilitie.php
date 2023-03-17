<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use DateTime;
use App\User;
use App\Client;
use App\Message;
use App\Group;

class Facilitie extends Model
{
    /**
     * カラムのリストを返す
     */
    public static function getKeys(){
        return Schema::getColumnListing((new Facilitie)->getTable());
    }

    /**
     * 施設管理者を返す
     */
    public function getAdmin(){
        return User::find($this->admin_id);
    }

    /**
     * 施設管理者の名前を返す
     */
    public function getAdminName(){
        $admin = User::find($this->admin_id);
        return isset($admin)? $admin->name : null;
    }

    // 施設職員用
    /**
     * 施設を利用する全ての児童を返す
     * @return Client[]
     */
    public function getClients(){
        return Client::where("facilitie_id", $this->id)->get();
    }

    /**
     * 指定されたidを持つclient(利用者)を取得する
     * @return Client | null
     */
    public function getClient($client_id){
        $clients = $this->getClients();
        return $clients->isEmpty() || $client_id==null? ["id"=>null]
               :($clients->find($client_id)?
               :($clients[0]));
    }

    /**
     * 雇用している職員を取得する
     * @return User[]
     */
    public function getWorkers(){
        $workers =  Worker::where("facilitie_id", $this->id)->where("active", true)->get();
        foreach($workers as $worker){
            $worker["user"] = $worker->getUser();
            $worker["careers"] = $worker->getCareers();
        }
        return $workers;
    }

    /**
     * 削除している職員を取得する
     * @return User[]
     */
    public function getDeletedWorkers(){
        $workers =  Worker::where("facilitie_id", $this->id)->where("active", false)->get();
        foreach($workers as $worker){
            $worker["user"] = $worker->getUser();
            $worker["careers"] = $worker->getCareers();
        }
        return $workers;
    }

    /**
     * 施設を利用する児童についてのメッセージを取得する
     */
    public function getClientMessageList(){
        $client_message_list = [];
        $sort = [];
        foreach($this->getClients() as $client){
            // 保護者が未登録の場合、スキップ
            if($client->getParent() == null) continue;

            $up_time = "";
            $unread = 0;
            // メッセージを取得
            $messages = Message::where("facilitie_id", $this->id)
                        ->where("client_id", $client->id)
                        ->orderBy('created_at','desc')->get();
            // 未読数をカウント
            foreach($messages as $message){
                if($message["from_id"]==$client->getChild()->parent_id){
                    if(!$message["readed"]) $unread++;
                }else{
                    break;
                }
            }
            // 最新のメッセージを取得
            $last_message = $messages->first();

            if(isset($last_message)){
                // 最終メッセージの時期
                $lt = $last_message->created_at;
                $d = ((new DateTime('now'))->diff(new DateTime($lt->format('Y-m-d'))));
                $up_time = (int)$d->format('%y')>0? $d->format('%y')."年前"
                         :((int)$d->format('%m')>0? $d->format('%m')."か月前"
                         :((int)$d->format('%d')>0? $d->format('%d')."日前"
                         :($lt->format('H:i'))));
                $sort[$client->id] = new DateTime($lt->format('Y-m-d H:i:s'));
            }else{
                // メッセージのやり取りがなければ、専用メッセージを生成
                $last_message["body"] = "施設とメッセージのやり取りができます";
                $sort[$client->id] = new DateTime("1000-01-01");
            }

            $client_message_list[$client->id] = [
                "other_id" => $client->getParent()->id,
                "facilitie_id" => $this->id,
                "client_id" => $client->id,
                "icon_path" => $client->icon_path,
                "name" => $client->name,
                "last_msg" => $last_message["body"],
                "updated" => $up_time,
                "unread" => $unread
            ];
        }

        // チャット情報を、最終メッセージの生成日時でソート
        array_multisort($sort, SORT_DESC, $client_message_list);

        return $client_message_list;
    }

    /**
     * 指定された日の全児童の記録を返す
     */
    public function getDiaries($date){
        return Diarie::whereIn('client_id', $this->getClients()->pluck("id"))->where('date', $date)->get();
    }

    /**
     * 指定された日の送迎記録を返す
     */
    public function getTransfer($date){
        $transfer = [
            "date" => $date,
            "trans" => []
        ];
        foreach($this->getClients() as $key => $client){
            $diarie = Diarie::where("date", $date)->where("client_id", $client->id)->first();
            if(!$diarie) continue;
            $transfer["trans"][$key] = [
                "client" => $client,
                "pick_depart_time" => $diarie["pick_depart_time"] ?? "",
                "pick_arrive_time" => $diarie["pick_arrive_time"] ?? "",
                "drop_depart_time" => $diarie["drop_depart_time"] ?? "",
                "drop_arrive_time" => $diarie["drop_arrive_time"] ?? "",
                "pick_addres" => $diarie["pick_addres"] ?? "",
                "drop_addres" => $diarie["drop_addres"] ?? ""
            ];

            $picker = User::find($diarie["pick_driver_id"]) ?? ["id"=>$diarie["pick_driver_id"],"name"=>$diarie["pick_driver_name"]];
            $transfer["trans"][$key]["pick_driver"] = $picker;
            $droper = User::find($diarie["drop_driver_id"]) ?? ["id"=>$diarie["drop_driver_id"],"name"=>$diarie["drop_driver_name"]];
            $transfer["trans"][$key]["drop_driver"] = $droper;

            $sign = SignImage::find($diarie["sign_id"]);
            $transfer["trans"][$key]["sign_path"] = isset($sign)? $sign["path"] : "";
        }

        return $transfer;
    }

    /**
     * 施設のグループを取得する
     */
     public function getGroups(){
        $groups_get = Group::where("facilitie_id", $this->id)->get();
        $groups = array();
        foreach($groups_get as $group){
            $group["client_ids"] = $group->getClientIds();
            $groups[$group["id"]] = $group;
        }

        return $groups;
     }

    /**
     *　今日の施設利用者を取得する
     */
     public function getTodayClient($date){
        $clients = $this->getClients();
        $clients_today = array();
        foreach ($clients as $client){
            $Diarie_today = Diarie::where("client_id", $client->id)->where("date", $date)->whereNotIn("service_type", [0])->first();
            if(isset($Diarie_today)){
                $clients_today[]  = $this->getClient($Diarie_today["client_id"]);
            }
        }
        return $clients_today;
     }

    /**
     *　指定された日の一括の記録を返す
     */
     public function getGroupItems($date){
        $clients = $this->getClients();
        $clients = json_decode(json_encode($clients), true);
        $client_ids = array_column($clients,'id');
        $diaries = Diarie::where("date", $date)->whereIn("client_id", $client_ids)->whereNotIn("service_type", [0])->get(["id"]);
        $diaries = json_decode(json_encode($diaries), true);
        $diarie_ids = array_column($diaries,'id');
        $diarie_items = DiarieItem::whereIn("diarie_id", $diarie_ids)->whereNotNull("share_item_id")->get(["share_item_id"]);
        $diarie_items = json_decode(json_encode($diarie_items), true);
        $share_item_id = array_column($diarie_items,'share_item_id');
        $group_items = DiarieItem::whereIn("id", $share_item_id)->orderBy('time','asc')->get();

        return $group_items;
     }

     /**
      *　絞り込み機能
      */
      public function batchClients($group_ids, $request_date, $old, $school_name){
          //施設利用してる子供たち取得
          if(isset($group_ids)){
              //グループ絞り込み
              $group_members = BelongingGroup::whereIn("group_id", $group_ids)->get();
              $clients = array();
              foreach ($group_members as $member) {
                  //グループで被りのある人排除
                  if(!in_array($member->client_id, array_column($clients, 'id'), true)){
                      $clients[] = Client::find($member->client_id);
                  }
              }
          }else{
              $clients = isset($this)? $this->getClients() : null;
          }
          //指定日付
          if(is_null($request_date)){
              $clients_day = $clients;
          }else{
              $date = $request_date;
              $clients_day = array();
              foreach ($clients as $client) {
                  $diarie_day = Diarie::where("client_id", $client->id)->where("date", $date)->whereNotIn("service_type", [0])->first();
                  if(isset($diarie_day)){
                      //日付指定の絞り込み
                      $clients_day[]  = $this->getClient($diarie_day["client_id"]);
                  }
              }
          }

          //年齢から生年月日範囲計算
          $clients_birth = array();
          if(isset($old)){
              $birthday_start = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + 1, date('Y') - $old - 1));
              $birthday_end = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d'), date('Y') - $old));
              //誕生日絞り込み
              foreach ($clients_day as $client) {
                  if($client->birthday >= $birthday_start && $client->birthday <= $birthday_end){
                      $clients_birth[] = $client;
                  }
              }
          }else{
              $clients_birth = $clients_day;
          }

          //学校絞り込み
          $clients = array();
          if(isset($school_name)){
              foreach ($clients_birth as $client) {
                  if($client->school_name == $school_name){
                      $clients[] = $client;
                  }
              }
          }else{
              $clients = $clients_birth;
          }

          return $clients;
      }

      /**
       *　クライアント(児童)登録
       */
       public function registClient($name, $birthday, $benefic_num, $school_name){
           $client = Client::regist($name, $birthday, $benefic_num, $school_name, $this);

           return $client;
       }

       /**
        * 職員権限設定
        */
       public function updateWorkerPermit($worker, $permit){
           $now = Date("Y-m-d H:i:s");
           $worker->permit = $permit;
           $worker->updated_at = $now;
           // 職員情報を更新
           $worker->save();

           return $worker;
       }

       /**
        * 職員承認
        */
       public function approvalWorker($worker, $worker_user_id){
           // 雇用情報の各項目にリクエストを代入
           $now = Date("Y-m-d H:i:s");
           $worker->user_id = $worker_user_id;
           $worker->active = true;
           $worker->updated_at = $now;
           // 雇用登録
           $worker->save();

           return $worker;
       }


}
