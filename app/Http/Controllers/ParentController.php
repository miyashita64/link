<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DateTime;
use App\User;
use App\Client;
use App\Child;
use App\Contract;
use App\Facilitie;
use App\Diarie;
use App\DiarieItem;
use App\ActiveImage;
use App\Message;

class ParentController extends Controller
{
    /**
     * コンストラクタ
     *
     * 未認証ならログイン画面へ遷移
     */
    public function __construct(){
        // 認証
        $this->middleware('auth');
        // 利用者がいなければ登録画面を表示
        if(Child::where("parent_id", Auth::id())->get()->isEmpty()){
            return redirect('./add_child');
        }
    }

    /**
     * 保護者ホーム画面
     *
     * 選択利用者の選択日の連絡帳閲覧
     */
    public function openHome(Request $request){
        $user = Auth::user();
        $child = $user->getChild($request->child_id);
        $date = $request->date ?: date("Y-m-d");
        $datas = [
            "title" => "保護者ホーム",
            "child" => $child,
            "children" => $user->getChildren(),
            "date" => $date,
            "clients" => [],
            "diaries" => [],
            "is_native" => $request->is_native,
        ];
        if(isset($child)){
            $datas["clients"] = $child->getClients();
            $datas["diaries"] = $child->getDiaries($date);
        }
        return view("parent.home", ["datas" => $datas]);
    }

    /**
     * 利用者登録ページ
     */
    public function openChildAdd(Request $request){
        $user = Auth::user();
        $child = $user->getChild($request->child_id);
        $date = $request->date ?: date("Y-m-d");
        $datas = [
            "title" => "利用者登録ページ",
            "child" => $child,
            "children" => $user->getChildren(),
            "is_native" => $request->is_native,
        ];
        return view("parent.add_child", ["datas" => $datas]);
    }

    /**
     * 利用者を登録
     */
    public function registChild(Request $request){
        $validate_rule = [
            'name' => 'required',
        ];
        $this->validate($request, $validate_rule);

        //児童登録
        Child::registChild($request);

        // 保護者ホーム画面を表示
        return redirect('./home');
    }

    /**
     * プロフィール編集画面
     */
    public function openProfile(Request $request){
        $user = Auth::user();
        $child = $user->getChild($request->child_id);
        $datas = [
            "title" => "利用者プロフィール",
            "child" => $child,
            "children" => $user->getChildren(),
            "is_native" => $request->is_native,
        ];
        return view("parent.profile", ["datas" => $datas]);
    }

    /**
     * クライアントプロフィール更新
     */
    public function updateChild(Request $request){
        $validate_rule = [
            'name' => 'required',
            'icon_img' => 'image|file'
        ];
        $this->validate($request, $validate_rule);

        // 選択利用者の取得
        $child = Child::find($request->id);
        //児童プロフィール更新
        $child->updateChild($request);

        // 保護者のホーム画面を表示
        return redirect()->back();
    }

    /**
     * 保護者プロフィール更新
     */
    public function updateParent(Request $request){
        $validate_rule = [
            'name' => 'required',
            'tel' => 'required',
            'icon_img' => 'image|file',
        ];
        $this->validate($request, $validate_rule);

        // 保護者情報を取得
        $parent = Auth::user();
        //プロフィール更新
        $parent->updateProfile($request);

        // プロフィール編集画面表示
        return redirect()->back();
    }

    /**
     * チャット相手リスト画面
     */
    public function openChatList(Request $request){
        $user = Auth::user();
        $child = $user->getChild($request->child_id);
        $message_list = [
            "facilitie" => $child->getMessageList(),
            "official" => $user->getOfficialChatList()
        ];
        $datas = [
            "title" => "施設一覧",
            "child" => $child,
            "children" => $user->getChildren(),
            "clients" => $child->getClients(),
            "message_list" => $message_list,
            "is_native" => $request->is_native,
        ];
        return view("parent.chat_list", ["datas" => $datas]);
    }

    /**
     * チャット画面
     */
    public function openChat(Request $request){
        $user = Auth::user();
        $child = $user->getChildByClientId($request->client_id);
        $client = $child->getClient($request->facilitie_id);
        $facilitie = (isset($client["id"]))? $client->getFacilitie() : null;
        $chats = (isset($facilitie))? $child->getMessages($request->facilitie_id)
                : $user->getMessages(null, null, $request->other_id);

        $datas = [
            "title" => "メッセージ画面",
            "worker_id" => -1,
            "child" => $child,
            "children" => $user->getChildren(),
            "client" => $client,
            "clients" => $child->getClients(),
            "facilitie" => $facilitie,
            "chats" => $chats,
            "is_native" => $request->is_native,
        ];
        return view("parent.chat", ["datas" => $datas]);
    }

    /**
     * メッセージ登録
     */
    public function registMessage(Request $request){
        $validate_rule = [
            'to_id' => 'required',
            'body' => 'required',
        ];
        $this->validate($request, $validate_rule);

        $client = Client::find($request->client_id);
        if(isset($client)){
            $facilitie = $client->getFacilitie();
            //メッセージ登録
            Message::regist($request->to_id, $client, $facilitie, $request->body);
        }

        // チャット画面を表示
        return redirect()->back();
    }

    /**
     * カレンダー画面を表示
     */
    public function openCalendar(Request $request){
        $user = Auth::user();
        $child = $user->getChild($request->child_id);
        $client = $child->getClient($request->facilitie_id);
        $datas = [
            "title" => "カレンダー",
            "child" => $child,
            "children" => $user->getChildren(),
            "client" => $client,
            "clients" => $child->getClients(),
            "is_native" => $request->is_native,
        ];
        return view("parent.calendar", ["datas" => $datas]);
    }
}
