<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;
use DateTime;
use App\User;
use App\Client;
use App\Contract;
use App\Facilitie;
use App\Diarie;
use App\DiarieItem;
use App\ActiveImage;
use App\Message;
use App\Worker;
use App\School;
use App\Teacher;

class LinkAdminController extends Controller
{
    /**
     * コンストラクタ
     *
     * 未認証ならログイン画面へ遷移
     */
    public function __construct(){
        // 認証
        $this->middleware('auth');
    }

    /**
     * 管理者ホーム画面
     *
     * 施設一覧
     */
    public function home(Request $request){
        $datas = [
            "title" => "リンク管理ホーム",
            "users" => User::all(),
            "facilities" => Facilitie::all(),
            "clients" => Client::all(),
            "diaries" => Diarie::all(),
            "workers" => Worker::all()
        ];

        return view("link_admin.home", ["datas" => $datas]);
    }

    /**
     * 凍結機能
     */
    public function freezeUser(Request $request){
        // 権限確認
        if(Auth::id()!=16){
            // レスポンス
            header("Content-type: application/json; charset=UTF-8");
            echo json_encode([
                "message" => "権限がありません",
            ]);
            exit();
        }

        // 凍結・解凍処理
        $user = User::find($request->freezer_id);
        if(isset($user)){
            $user->active = $request->freezer_value;
            $user->save();

            // レスポンス
            header("Content-type: application/json; charset=UTF-8");
            echo json_encode([
                "message" => "[".$user->id.": ".$user->name."]->active = ".$user->active
            ]);
            exit();
        }

        // レスポンス
        header("Content-type: application/json; charset=UTF-8");
        echo json_encode([
            "message" => "該当するユーザーは存在しません",
        ]);
        exit();
    }

    /**
     * チャットリストページ
     */
    public function openChatList(Request $request){
        $user = Auth::User();
        $message_list = $user->getOfficialChatListForAdmin();
        $datas = [
            "title" => "チャット一覧",
            "message_list" => $message_list
        ];
        return view("link_admin.chat_list", ["datas" => $datas]);
    }

    /**
     * チャット画面
     */
    public function openChat(Request $request){
        $user = Auth::User();
        $chats = $user->getMessages(null, null, $request->other_id);
        $datas = [
            "title" => "メッセージ画面",
            "client" => null,
            "facilitie" => null,
            "chats" => $chats
        ];
        return view("link_admin.chat", ["datas" => $datas]);
    }

    /**
     * メッセージ登録
     */
    public function registMessage(Request $request){
        $validate_rule = [
            'to_id' => 'required',
        ];
        $this->validate($request, $validate_rule);

        // メッセージの各項目にリクエストを代入
        $now = Date("Y-m-d H:i:s");
        $message = new Message;
        $message->from_id = Auth::id();
        $message->to_id = $request->to_id;
        $message->client_id = null;
        $message->facilitie_id = null;
        $message->body = $request->body;
        $message->readed = false;
        $message->active = true;
        $message->created_at = $now;
        $message->updated_at = $now;
        // メッセージ登録
        $message->save();

        // チャット画面を表示
        return redirect()->back();
    }

    /**
     * 新規施設登録
     */
    public function registFacilitie(Request $request){
        $validate_rule = [
            'name' => 'required',
            'office_number' => 'required',
            'admin_id' => 'required',
        ];
        $this->validate($request, $validate_rule);

        // 各要素を代入
        $now = Date("Y-m-d H:i:s");
        $facilitie = new Facilitie;
        $facilitie->name = $request->name;
        if(isset($request->light_name)) $facilitie->light_name = $request->light_name;
        $facilitie->office_number = $request->office_number;
        $facilitie->admin_id = $request->admin_id;
        $facilitie->icon_path = "../img/logo/home.png";
        $facilitie->active = true;
        $facilitie->created_at = $now;
        $facilitie->updated_at = $now;
        // 施設登録
        $facilitie->save();

        // 管理者の雇用情報を追加
        $worker = new Worker;
        $worker->facilitie_id = $facilitie->id;
        $worker->user_id = $facilitie->admin_id;
        $worker->name = User::find($facilitie->admin_id)->name;
        $worker->permit = 2;  // 施設管理者権限
        $worker->active = true;
        $worker->created_at = $now;
        $worker->updated_at = $now;
        $worker->save();

        // 保護者ホーム画面を表示
        return redirect('./home');
    }

    /**
     * 新規学校登録
     */
    public function registSchool(Request $request){
        $validate_rule = [
            'name' => ['required', 'string', 'max:60'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'tel' => ['required', 'string', 'max:15'],
            'teacher_name' => ['required', 'string'],
        ];
        $this->validate($request, $validate_rule);

        $now = Date("Y-m-d H:i:s");
        // ログイン用のアカウントを追加
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user-> tel = $request->tel;
        $user->role = config("const.Roles.TEACHER");  // 教員権限
        $user->active = true;
        $user->created_at = $now;
        $user->updated_at = $now;
        $user->save();

        // 各要素を代入
        $school = new School;
        $school->name = $request->name;
        $school->admin_id = $user->id;
        $school->active = true;
        $school->created_at = $now;
        $school->updated_at = $now;
        $school->save();

        // 初期教員の追加
        $teacher = new Teacher;
        $teacher->name = $request->teacher_name;
        $teacher->school_id = $school->id;
        $teacher->created_at = $now;
        $teacher->updated_at = $now;
        $teacher->save();

        // 保護者ホーム画面を表示
        return redirect('./home');
    }

    /**
     * ユーザの権限変更
     */
    public function updateUserPermit(Request $request){
        $validate_rule = [
            'user_id' => 'required',
            'permit' => 'required',
        ];
        $this->validate($request, $validate_rule);

        // 権限確認
        if(Auth::user()->email!="javajavawookjavawook@gmail.com"){
            return redirect('./home');
        }

        $user = User::find($request->user_id);
        // 指定ユーザが存在すれば、権限を更新
        if(isset($user) && $user->id != 16 && $request->permit >= 0){
            $user->role = $request->permit;
            $user->save();
        }

        return redirect('./home');
    }

    /**
     * ユーザのパスワード変更
     */
    public function updateUserPassword(Request $request){
        $validate_rule = [
            'user_id' => 'required',
            'new_password' => 'required',
        ];
        $this->validate($request, $validate_rule);

        // 権限確認
        if(Auth::user()->email!="javajavawookjavawook@gmail.com"){
            return redirect('./home');
        }

        $user = User::find($request->user_id);
        // 指定ユーザが存在すれば、パスワードを更新
        if(isset($user)){
            $user->password = Hash::make($request->new_password);
            $user->save();
        }

        return redirect('./home');
    }
}
