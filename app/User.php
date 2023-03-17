<?php

namespace App;

use Auth;
use DateTime;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Schema;
use App\Notifications\CustomResetPassword;
use App\Contract;
use App\Worker;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'tel', 'role', 'active'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function getKeys(){
        return Schema::getColumnListing((new User)->getTable());
    }

    /**
     * パスワード再設定メールの送信
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token){
        $this->notify(new CustomResetPassword($token));
    }

    /**
     * 運営とのチャットを取得
     */
    public function getOfficialChatList(){
        $lt = new DateTime("9999-12-31");
        $ownId = $this->id;
        $other_user = User::find(1);    // システム管理者(ID:1)
        // 選択職員ID > 利用施設ID のメッセージを生成日時でソートし取得
        $messages = Message::where(function($query) use ($ownId){
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
            $last_body = "お問い合わせはコチラにお寄せください";
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
        // 他のチャットリストと同じ構造にするため、2次元配列としている
        return [[
            "other_id" => $other_user->id,
            "facilitie_id" => null,
            "client_id" => null,
            "icon_path" => $other_user->icon_path,
            "name" => $other_user->name,
            "last_msg" => $last_body,
            "last_dt" => $lt,
            "updated" => $up_time,
            "unread" => $unread
        ]];
    }

    /**
     * 運営のチャットリストを返す
     */
    public function getOfficialChatListForAdmin(){
        $worker_message_list = [];
        $worker_sort = [];
        $parent_message_list = [];
        $parent_sort = [];
        $exedIds = [];

        $ownId = 1; // 運営アカウント
        $all_messages = Message::where(function($query) use ($ownId){
            $query->orWhere('to_id', $ownId)->orWhere('from_id', $ownId);
        })->orderBy('created_at','desc')->get();
        foreach($all_messages as $key => $message){
            $lt = new DateTime("9999-12-31");
            $other_id = ($message->from_id==$ownId)? $message->to_id : $message->from_id;
            $other_user = User::find($other_id);
            if(!isset($other_user) || in_array($other_user->id, $exedIds)) continue;
            // メッセージを生成日時でソートし取得
            $messages = Message::where(function($query) use ($ownId){
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
                $last_body = "本ユーザからのお問い合わせありません";
            }

            // 未読メッセージをカウント
            $unread = 0;
            foreach($messages as $message){
                if($message["from_id"]==$other_user->id){
                    if(!$message["readed"]) $unread++;
                }else{
                    break;
                }
            }

            if($other_user->role == config('const.Roles.WORKER')){
                // 関連情報を施設ごとにまとめる
                $worker_message_list[$other_user->id] = [
                    "other_id" => $other_user->id,
                    "facilitie_id" => null,
                    "client_id" => null,
                    "icon_path" => $other_user->icon_path,
                    "name" => $other_user->name,
                    "last_msg" => $last_body,
                    "last_dt" => $lt,
                    "updated" => $up_time,
                    "unread" => $unread
                ];

                // 施設ごとの最終メッセージの生成日時を配列化
                $worker_sort[$key] = $lt? new DateTime($lt->format('Y-m-d H:i:s')) : new DateTime("9999-12-31");
            }else{
                // 関連情報を施設ごとにまとめる
                $parent_message_list[$other_user->id] = [
                    "other_id" => $other_user->id,
                    "facilitie_id" => null,
                    "client_id" => null,
                    "icon_path" => $other_user->icon_path,
                    "name" => $other_user->name,
                    "last_msg" => $last_body,
                    "last_dt" => $lt,
                    "updated" => $up_time,
                    "unread" => $unread
                ];

                // 施設ごとの最終メッセージの生成日時を配列化
                $parent_sort[$key] = $lt? new DateTime($lt->format('Y-m-d H:i:s')) : new DateTime("9999-12-31");
            }
            $exedIds[] = $other_user->id;
        }

        // チャット情報を、最終メッセージの生成日時でソート
        array_multisort($worker_sort, SORT_DESC, $worker_message_list);
        array_multisort($parent_sort, SORT_DESC, $parent_message_list);

        return [
            "worker" => $worker_message_list,
            "parent" => $parent_message_list
        ];
    }

    // 保護者用
    /**
     * 扶養する全てのclient(児童)を取得する
     * @return Client[]
     */
    public function getChildren(){
        return Child::where("parent_id", $this->id)->get();
    }

    /**
     * 指定されたidを持つchild(児童)を取得する
     * @return Child | null
     */
    public function getChild($child_id){
        $children = $this->getChildren();
        return $children->isEmpty()? null
               :($children->find($child_id)?
               :($children[0]));
    }

    /**
     * 指定されたclient_idを持つchild(児童)を取得する
     * @return Child | null
     */
    public function getChildByClientId($client_id){
        $children  = $this->getChildren();
        $client = Client::find($client_id);
        return $children->isEmpty()? null
               :(isset($client)? $children->find($client["child_id"])
               : $children[0]);
    }

    /**
     * 扶養する児童の全てのcontract(施設利用)を取得する
     * @return Contract[]
     */
    public function getContracts(){
        $contracts = [];
        foreach($this->getChildren() as $child){
            $contracts[] = Contract::where("client_id", $child->getClient()->id)->get();
        }
        return $contracts;
    }

    // 職員用
    public function getWorker($facilitie_id){
        $workers = Worker::where("user_id", $this->id)->where("active", true)->get();
        return $workers->isEmpty()? null
              :($workers->where("facilitie_id", $facilitie_id)->first()?
              :($workers[0]));
    }

    /**
     * 指定された相手とのメッセージを取得する
     */
    public function getMessages($facilitie_id, $client_id, $other_id){
        // 選択中の施設を取得
        $facilitie = Facilitie::find($facilitie_id);
        /*
        if(!isset($facilitie)){
            redirect()->back();
        }
        */

        if($client_id != null){
            // 施設から保護者へのメッセージの場合
            // 選択中の利用者を取得
            $client = $facilitie->getClient($client_id);
            // 選択利用者 > 選択施設 のメッセージを生成日時でソートし取得
            $message_values = Message::where('client_id', $client->id)->orderBy("created_at","asc")->get();
            // チャットの基本情報を取得
            $messages = [
                "own_id" => $this->id,
                "own_icon" => $this->icon_path,
                "other_id" => $client->getParent()->id,
                "other_icon" => $client->icon_path,
                "other_name" => $client->name,
                "facilitie_id" => $facilitie->id,
                "client_id" => $client->id,
                "chats" => []
            ];
        }else if($facilitie_id != null){
            // 施設内でのメッセージの場合
            // 選択中の職員
            $worker = User::find($other_id);
            $id = $this->id;
            // 選択職員 > 選択施設 のメッセージを生成日時でソートし取得
            $message_values = Message::where('client_id', null)->where('facilitie_id', $facilitie->id)->
                where(function($query) use ($id){
                    $query->orWhere('to_id', $id)->orWhere('from_id', $id);
                })->where(function($query) use ($worker){
                    $query->orWhere('to_id', $worker->id)->orWhere('from_id', $worker->id);
                })->orderBy("created_at","asc")->get();

            // チャットの基本情報を取得
            $messages = [
                "own_id" => $this->id,
                "own_icon" => $this->icon_path,
                "other_id" => $worker->id,
                "other_icon" => $worker->icon_path,
                "other_name" => $worker->name,
                "facilitie_id" => $facilitie->id,
                "client_id" => null,
                "chats" => []
            ];
        }else{
            // 運営とのチャット
            $other = User::find($other_id);
            $id = ($this->role <= config('const.Roles.ADMIN'))? 1 : $this->id;    // 運営の場合は、専用ID(1)を使用する
            // 選択職員 > 選択施設 のメッセージを生成日時でソートし取得
            $message_values = Message::where(function($query) use ($id){
                    $query->orWhere('to_id', $id)->orWhere('from_id', $id);
                })->where(function($query) use ($other){
                    $query->orWhere('to_id', $other->id)->orWhere('from_id', $other->id);
                })->orderBy("created_at","asc")->get();

            // チャットの基本情報を取得
            $messages = [
                "own_id" => $id,
                "own_icon" => $this->icon_path,
                "other_id" => $other->id,
                "other_icon" => $other->icon_path,
                "other_name" => $other->name,
                "facilitie_id" => null,
                "client_id" => null,
                "chats" => []
            ];
        }
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
            if($message["from_id"]!=Auth::id() && !$message["readed"]){
                $message["readed"] = true;
                $message->save();
            }
            // メッセージ内容を格納
            $messages["chats"][$i]["messages"][$j++] = [
                "time" => $message["created_at"]->format('H:i'),
                "send_fg" => $message["from_id"]!=$other_id,
                "text" => $message["body"],
                "readed" => $message["readed"]
            ];
        }

        return $messages;
    }

    /**
     * プロフィール更新(保護者・施設職員)
     */
    public function updateProfile($request){
        $this->name = $request->name;
        $this->tel = $request->tel;
        // プロフィール画像がアップロードされた場合
        if($file = $request->icon_img){
            $path = public_path("img/user_img/users/");
            $fileName = $this->id.".".$file->getClientOriginalExtension();
            // アップロードされたプロフィール画像を保存
            $file->move($path, $fileName);
            // 画像へのパスを登録
            $this->icon_path = "../img/user_img/users/".$fileName;
        }
        $now = Date("Y-m-d H:i:s");
        $this->updated_at = $now;
        // 利用者情報を更新
        $this->save();

        return $this;
    }
}
