<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Auth;
use DateTime;
use App\User;
use App\Facilitie;
use App\Contract;
use App\Client;
use App\Child;
use App\Diarie;
use App\DiarieItem;
use App\ActiveImage;
use App\SignImage;
use App\Message;
use App\Worker;
use App\Career;
use App\Group;
use App\BelongingGroup;
use App\Excel;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Shared\File;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class WorkerController extends Controller
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
     * 職員ホーム画面
     *
     * 利用者一覧を渡す
     */
    public function openHome(Request $request){
        $user = Auth::User();
        $worker = $user->getWorker($request->facilitie_id);
        $facilitie = isset($worker)? $worker->getEmployedFacilitie($worker->facilitie_id) : null;
        $datas = [
            "title" => "職員ホーム",
            "facilitie" => $facilitie,
            "facilities" => isset($worker)? $worker->getEmployedFacilities() : null,
            "clients" => isset($facilitie)? $facilitie->getClients() : null,
            "is_native" => $request->is_native,
        ];

        // 職員ホーム、利用者一覧画面を表示
        return view("worker.home", ["datas" => $datas]);
    }

    /**
     * 利用者追加画面
     */
    public function openClientAdd(Request $request){
        $user = Auth::User();
        $worker = $user->getWorker($request->facilitie_id);
        $facilitie = isset($worker)? $worker->getEmployedFacilitie($worker->facilitie_id) : null;
        $datas = [
            "title" => "利用者登録",
            "facilitie" => $facilitie,
            "facilities" => isset($worker)? $worker->getEmployedFacilities() : null,
            "is_native" => $request->is_native,
        ];

        // 利用者登録画面を表示
        return view("worker.add_client", ["datas" => $datas]);
    }

    /**
     * 新規利用者登録
     */
    public function registClient(Request $request){
        $validate_rule = [
            'facilitie_id' => 'required',
            'name' => 'required',
            'birthday' => 'required',
            'benefic_num' => 'required',
            'school_name' => 'required',
        ];
        $this->validate($request, $validate_rule);

        $user = Auth::user();
        $worker = $user->getWorker($request->facilitie_id);
        $facilitie = isset($worker)? $worker->getEmployedFacilitie($worker->facilitie_id) : null;

        if(isset($facilitie)){
            $facilitie->registClient($request->name, $request->birthday, $request->benefic_num, $request->school_name);
        }
        // 職員ホーム画面を表示
        return redirect('./home?facilitie_id='.$request->facilitie_id);
    }

    /**
     * プロフィール編集画面
     */
    public function openProfile(Request $request){
        $user = Auth::User();
        $worker = $user->getWorker($request->facilitie_id);
        $facilitie = isset($worker)? $worker->getEmployedFacilitie($worker->facilitie_id) : null;
        $datas = [
            "title" => "職員プロフィール",
            "facilitie" => $facilitie,
            "facilities" => isset($worker)? $worker->getEmployedFacilities() : null,
            "clients" => isset($facilitie)? $facilitie->getClients() : null,
            "is_native" => $request->is_native,
        ];
        return view("worker.profile", ["datas" => $datas]);
    }

    /**
     * 職員プロフィール更新
     */
    public function updateWorkerProfile(Request $request){
        $validate_rule = [
            'id' => 'required',
            'name' => 'required',
            'tel' => 'required',
            'icon_img' => 'image|file'
        ];
        $this->validate($request, $validate_rule);

        // ユーザID照合
        if($request->id==Auth::id()){
            // 職員情報を取得
            $user = Auth::user();
            $user->updateProfile($request);
        }

        // 職員ホーム画面表示
        return redirect()->back();
    }

    /**
     * サービス記録編集画面
     *
     * サービス記録情報を渡す
     */
    public function openDiarieEditor(Request $request){
        $user = Auth::User();
        $worker = $user->getWorker($request->facilitie_id);
        $facilitie = isset($worker)? $worker->getEmployedFacilitie($worker->facilitie_id) : null;
        $client = isset($worker)? $facilitie->getClient($request->client_id) : null;
        $date = $request->date ?: date("Y-m-d");
        $items = $worker->getWorkerHistories();

        $datas = [
            "title" => "連絡帳記入",
            "facilitie" => $facilitie,
            "facilities" => isset($worker)? $worker->getEmployedFacilities() : null,
            "client" => $client,
            "clients" => $facilitie->getClients(),
            "date" => $date,
            "diarie" => $client->getDiarie($date),
            "active_imgs" => $client->getActiveImages($date),
            "items" => $items,
            "is_native" => $request->is_native,
        ];

        // サービス記録編集画面を表示
        return view("worker.edit_diarie", ["datas" => $datas]);
    }

    /**
     * 活動写真をアップロード
     */
    public function uploadActiveImage(Request $request){
        $validate_rule = [
            // 'active_imgs' => 'image|file'
        ];
        $this->validate($request, $validate_rule);
        $user = Auth::User();
        $worker = $user->getWorker($request->facilitie_id);
        $facilitie = $worker->getEmployedFacilitie($worker->facilitie_id);
        $client = Client::find($request->client_id);
        $diarie = Diarie::find($request->diarie_id);

        // 活動写真がアップロードされた場合
        if(isset($request->active_imgs)){
            foreach($request->active_imgs as $file){
                $tmp = "img/user_img/active_img/".$client->id."/".$request->date."/".$facilitie->id."/";
                $worker->uploadActiveImage($diarie, $client, $request->date, $facilitie, $file, $tmp);
            }
        }

        // レスポンス
        exit();
    }

    /**
     * 連絡帳を更新
     *
     * サービス記録を更新し、サービス記録編集画面を表示
     */
    public function updateDiarieItem(Request $request){
        $validate_rule = [
            //'client_id' => 'required',
            //'facilitie_id' => 'required',
            //'date' => 'required',
            'time' => 'required|date_format:H:i',
            'activity' => 'required'
        ];
        $this->validate($request, $validate_rule);
        if($request->diarie_id < 0){
            // サービス記録が存在しない場合、ブラウザバック
            return redirect()->back();
        }

        // 既存のサービス記録を取得・時間更新
        $diarie = Diarie::find($request->diarie_id);
        if(is_null($diarie)){
            return redirect()->back();
        }
        $diarie->updateTime();

        // 連絡帳レコードが存在すれば取得、なければ生成
        $share_item_id = null;
        if($request->id < 0){
            DiarieItem::regist($diarie, $request->time, $request->activity, $request->comment, $request->parent_hidden, $share_item_id);
        }else{
            $diarie_item = DiarieItem::find($request->id);
            if(!is_null($diarie_item)){
                $diarie_item->updateItem($diarie, $request->time, $request->activity, $request->comment, $request->parent_hidden, $share_item_id);
            }
        }

        return redirect()->back();
    }

    /**
     * 一括入力画面
     */
    public function openBatchEditDiarie(Request $request){
        $user = Auth::User();
        $worker = $user->getWorker($request->facilitie_id);
        $facilitie = $worker->getEmployedFacilitie($worker->facilitie_id);
        $date = $request->date ?: date("Y-m-d");
        $items = $worker->getWorkerHistories();
        $groups = $facilitie->getGroups();
        $clients_today = isset($facilitie)? $facilitie->getTodayClient($date) : null;
        $group_items = $facilitie->getGroupItems($date);

        $datas = [
            "title" => "一括入力",
            "facilitie" => $facilitie,
            "facilities" => $worker->getEmployedFacilities(),
            "date" => $date,
            "clients" => $clients_today,
            "items" => $items,
            "groups" => $groups,
            "group_items" => $group_items,
            "is_native" => $request->is_native,
        ];

        // 職員ホーム、利用者一覧画面を表示
        return view("worker.batch_edit_diarie", ["datas" => $datas]);
    }

    /**
     * 一括入力時の絞り込み
     */
    public function batchSearch(Request $request){
        $validate_rule = [
            'facilitie_id' => 'required',
        ];
        $user = Auth::User();
        $worker = $user->getWorker($request->facilitie_id);
        $facilitie = $worker->getEmployedFacilitie($worker->facilitie_id);
        //絞り込み
        $clients = $facilitie->batchClients($request->group_ids, $request->date, $request->old, $request->school_name);

        // レスポンス
        header("Content-type: application/json; charset=UTF-8");
        echo json_encode([
            "clients" => $clients
        ]);
        exit();
    }

    /**
     * 一括入力の登録
     */
    public function registBatchActivity(Request $request){
        $validate_rule = [
            'time' => 'required|date_format:H:i',
            'activity' => 'required'
        ];
        $this->validate($request, $validate_rule);

        $user = Auth::User();
        $worker = $user->getWorker($request->facilitie_id);
        $date = $request->date ?: date("Y-m-d");

        $worker->updateBatchActivity($request->client_ids, $request->time, $request->activity, $request->comment, $request->parent_hidden, $request->date, $request->share_item_id);

        // レスポンス
        exit();
    }

    /**
     * 一括入力の削除
     */
    public function deleteBatchActivity(Request $request){
        $validate_rule = [
            'share_item_id' => 'required',
        ];
        $this->validate($request, $validate_rule);
        $user = Auth::User();
        $worker = $user->getWorker($request->facilitie_id);
        //一括削除
        $worker->deleteBatchActivity($request->share_item_id);

        exit();
    }

    /**
     * 一括画像追加
     */
    public function uploadBatchActiveImage(Request $request){
        $validate_rule = [
            'client_ids' => 'required',
            // 'active_imgs' => 'image|file'
        ];
        $this->validate($request, $validate_rule);

        $user = Auth::User();
        $worker = $user->getWorker($request->facilitie_id);
        $facilitie = $worker->getEmployedFacilitie($worker->facilitie_id);

        // 活動写真がアップロードされた場合
        if(isset($request->active_imgs)){
            foreach($request->active_imgs as $file){
                foreach($request->client_ids as $key => $client_id){
                    $client = Client::find($client_id);
                    if($key === 0){
                        $diarie = Diarie::where("client_id", $client->id)->where("date", $request->date)->first(["id"]);
                        $tmp = "img/user_img/active_img/Batch/".$request->date."/".$facilitie->id."/";
                        $actImg = $worker->uploadActiveImage($diarie, $client, $request->date, $facilitie, $file, $tmp);
                    }else{
                        $worker->uploadBatchActiveImage($client, $request->date, $facilitie, $actImg->path);
                    }
                }
            }
        }
        // レスポンス
        exit();
    }

    /**
     * 一括メッセージ送信
     */
    public function registBatchMessage(Request $request){
        $validate_rule = [
            'client_ids' => 'required',
            'facilitie_id' => 'required',
            'body' => 'required'
        ];
        $this->validate($request, $validate_rule);

        $user = Auth::User();
        $worker = $user->getWorker($request->facilitie_id);
        $facilitie = $worker->getEmployedFacilitie($worker->facilitie_id);

        //一括メッセージ送信
        foreach ($request->client_ids as $client_id){
            $client = Client::find($client_id);
            $child = Child::find($client->child_id);
            Message::regist($child->parent_id, $client, $facilitie, $request->body);
        }

        //レスポンス
        exit();
    }

    /**
     * グループ作成・更新機能
     */
    public function createUpdateGroup(Request $request){
        $validate_rule = [
            'name' => 'required',
            'client_ids' => 'required',
            'facilitie_id' => 'required',
        ];
        $this->validate($request, $validate_rule);

        $user = Auth::User();
        $worker = $user->getWorker($request->facilitie_id);
        $facilitie = $worker->getEmployedFacilitie($worker->facilitie_id);

        //メンバーいない状態のチェック
        if(count(array_filter($request->client_ids)) == 0){
            exit();
        }

        if($request->group_id <= 0){
            $group = Group::regist($request->name, $facilitie);
        }else{
            $group = Group::find($request->group_id);
            if(is_null($group)) exit();
            $group->updateGroup($request->name, $facilitie);
        }

        //メンバー登録
        BelongingGroup::where("group_id", $group->id)->delete();
        foreach ($request->client_ids as $client_id){
            $client = Client::find($client_id);
            if(!is_null($client)){
                BelongingGroup::regist($group, $client);
            }
        }

        // レスポンス
        exit();
    }

    /**
     * グループ削除機能
     */
     public function deleteGroup(Request $request){
        $validate_rule = [
            'group_id' => 'required',
        ];
        $this->validate($request, $validate_rule);
        //グループ削除
        $group = Group::find($request->group_id);
        if($group){
            BelongingGroup::where("group_id", $group->id)->delete();
            $group->delete();
        }
        return redirect()->back();
     }

    /**
     * 連絡帳の項目を削除
     */
    public function deleteDiarieItem(Request $request){
        $validate_rule = [
            'id' => 'required',
        ];
        $this->validate($request, $validate_rule);
        //連絡帳削除
        $diarieItem = DiarieItem::find($request->id);
        if($diarieItem){
            $diarie = Diarie::find($diarieItem->diarie_id);
            if(isset($diarie)){
                // 連絡帳レコードが存在すれば削除
                $diarieItem->delete();
            }
        }
        // サービス記録編集画面の表示
        return redirect()->back();
    }

    /**
     * サービス記録を更新（コメント・共有情報）
     */
    public function updateDiarieDocument(Request $request){
        $validate_rule = [
            'id' => 'required',
            'client_id' => 'required',
            'facilitie_id' => 'required',
            'date' => 'required',
        ];
        $this->validate($request, $validate_rule);

        if($request->id < 0){
            // サービス記録が存在しない場合、生成
            $client = Client::find($request->client_id);
            $facilitie = Facilitie::find($request->facilitie_id);
            $diarie = Diarie::regist($client, $request->date);
        }else{
            // 既存のサービス記録を取得
            $diarie = Diarie::find($request->id);
        }

        //書類更新
        $diarie->updateDocument($request);

        // サービス記録編集画面を表示
        return redirect('./worker/edit_diarie?client_id='.$request->client_id.'&facilitie_id='.$request->facilitie_id.'&date='.$request->date);
    }

    /**
     * 印刷用PDF生成
     */
    public function openDiariePDF(Request $request){
        $user = Auth::User();
        $worker = $user->getWorker($request->facilitie_id);
        $facilitie = isset($worker)? $worker->getEmployedFacilitie($worker->facilitie_id) : null;
        $client = $facilitie->getClient($request->client_id);
        $date = $request->date ?: date("Y-m-d");
        $datas = [
            "facilitie" => $facilitie,
            "client" => $client,
            "clients" => $facilitie->getClients(),
            "date" => $date,
            "diarie" => $client->getDiarie($date),
            "active_imgs" => $client->getActiveImages($date),
            "is_native" => $request->is_native,
        ];
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('pdf.diarie', ["datas"=>$datas]);

        return $pdf->stream('diarie.pdf');
    }

    /**
     * チャット相手リスト画面
     */
    public function openChatList(Request $request){
        $user = Auth::User();
        $worker = $user->getWorker($request->facilitie_id);
        $facilitie = isset($worker)? $worker->getEmployedFacilitie($worker->facilitie_id) : null;
        $message_list = isset($worker)? $worker->getWorkerMessageList($facilitie->id) : [];
        $datas = [
            "title" => "チャット一覧",
            "facilitie" => $facilitie,
            "facilities" => isset($worker)? $worker->getEmployedFacilities() : null,
            "clients" => $facilitie->getClients(),
            "message_list" => $message_list,
            "is_native" => $request->is_native,
        ];
        return view("worker.chat_list", ["datas" => $datas]);
    }

    /**
     * チャット画面
     */
    public function openChat(Request $request){
        $user = Auth::User();
        $worker = $user->getWorker($request->facilitie_id);
        $facilitie = isset($worker)? $worker->getEmployedFacilitie($worker->facilitie_id) : null;
        $client = $facilitie->getClient($request->client_id);
        $facilitie_id = isset($facilitie)? $facilitie["id"] : null;
        $client_id = isset($client)? $client["id"] : null;
        $chats = $user->getMessages($facilitie_id, $client_id, $request->other_id);
        $datas = [
            "title" => "メッセージ画面",
            "worker_id" => null,
            "facilitie" => $facilitie,
            "facilities" => isset($worker)? $worker->getEmployedFacilities() : null,
            "client" => $client,
            "chats" => $chats,
            "is_native" => $request->is_native,
        ];
        return view("worker.chat", ["datas" => $datas]);
    }

    /**
     * メッセージ登録
     */
    public function registMessage(Request $request){
        $validate_rule = [
            'to_id' => 'required',
            //'client_id' => 'required', // nullもありのため一旦
            'facilitie_id' => 'required',
            'body' => 'required',
        ];
        $this->validate($request, $validate_rule);

        $user = Auth::User();
        $worker = $user->getWorker($request->facilitie_id);
        $facilitie = isset($worker)? $worker->getEmployedFacilitie($worker->facilitie_id) : null;
        $client = isset($request->client_id)? (Client::find($request->client_id)) : null;

        //メッセージ登録
        Message::regist($request->to_id, $client, $facilitie, $request->body);

        // チャット画面を表示
        return redirect()->back();
    }

    /**
     * レポート機能リスト
     */
    public function openReportList(Request $request){
        $user = Auth::User();
        $worker = $user->getWorker($request->facilitie_id);
        $facilitie = isset($worker)? $worker->getEmployedFacilitie($worker->facilitie_id) : null;
        $client = $facilitie->getClient($request->client_id);
        $datas = [
            "title" => "書類管理",
            "facilitie" => $facilitie,
            "facilities" => isset($worker)? $worker->getEmployedFacilities() : null,
            "is_native" => $request->is_native,
        ];
        return view("worker.report_list", ["datas" => $datas]);
    }

    /**
     * サービス管理
     */
    public function openServiceManagement(Request $request){
        $user = Auth::User();
        $worker = $user->getWorker($request->facilitie_id);
        $facilitie = isset($worker)? $worker->getEmployedFacilitie($worker->facilitie_id) : null;
        $client = $facilitie->getClient($request->client_id);
        $date = $request->date ?: date("Y-m-d");
        $datas = [
            "title" => "連絡帳記入",
            "facilitie" => $facilitie,
            "facilities" => isset($worker)? $worker->getEmployedFacilities() : null,
            "workers" => $facilitie->getWorkers(),
            "client" => $client,
            "clients" => $facilitie->getClients(),
            "date" => $date,
            "diaries"=> $facilitie->getDiaries($date),
            "is_native" => $request->is_native,
        ];

        return view("worker.service_management", ["datas" => $datas]);
    }

    /**
     * サービス情報の更新
     */
    public function updateServiceDocument(Request $request){
        $validate_rule = [
            'id' => 'required',
            'client_id' => 'required',
            'facilitie_id' => 'required',
            'body' => 'required',
        ];

        if($request->id < 0){
            // サービス記録が存在しない場合、生成
            $client = Client::find($request->client_id);
            $facilitie = Facilitie::find($request->facilitie_id);
            $diarie = Diarie::regist($client, $request->date);
        }else{
            // 既存のサービス記録を取得
            $diarie = Diarie::find($request->id);
        }

        //サービス記録更新
        $diarie->updateServiceDocument($request);

        // サービス記録編集画面を表示
        return redirect('./worker/service_management?facilitie_id='.$request->facilitie_id.'&date='.$request->date);
    }

    /**
     * サービス提供記録
     */
    public function openServiceReportList(Request $request){
        $user = Auth::User();
        $worker = $user->getWorker($request->facilitie_id);
        $facilitie = isset($worker)? $worker->getEmployedFacilitie($worker->facilitie_id) : null;
        $client = $facilitie->getClient($request->client_id);
        $date = $request->date ?: date("Y-m-d");
        $datas = [
            "title" => "サービス提供記録",
            "facilitie" => $facilitie,
            "facilities" => isset($worker)? $worker->getEmployedFacilities() : null,
            "client" => $client,
            "clients" => $facilitie->getClients(),
            "date" => $date,
            "is_native" => $request->is_native,
        ];

        return view("worker.service_report", ["datas" => $datas]);
    }

    /**
     * サービス提供記録確認ページ
     */
    public function openServiceReportView(Request $request){
        $user = Auth::User();
        $worker = $user->getWorker($request->facilitie_id);
        $facilitie = isset($worker)? $worker->getEmployedFacilitie($worker->facilitie_id) : null;
        $client = $facilitie->getClient($request->client_id);
        $date = $request->date ?: date("Y-m-d");
        $datas = [
            "title" => "サービス提供記録確認",
            "facilitie" => $facilitie,
            "facilities" => isset($worker)? $worker->getEmployedFacilities() : null,
            "client" => $client,
            "date" => $date,
            "is_native" => $request->is_native,
        ];

        $ym = explode("-", $datas["date"]);
        if(!$client || !$facilitie) exit();
        if(count($ym)<2) exit();

        $datas["diaries"] = Diarie::where("client_id", $client->id)->where("date", ">=", $ym[0]."-".$ym[1])->where("date", "<=", $ym[0]."-".substr("0".($ym[1]+1),-2))->get();
        foreach($datas["diaries"] as $diarie){
            foreach(["defication"=>"排泄","hydration"=>"水分補給","medication"=>"服薬"] as $key => $item){
                $diarie[$key] = DiarieItem::where("diarie_id", $diarie["id"])->where("activity", $item)->orderBy("time", "asc")->get();
            }
        }
        return view("worker.service_report_view", ["datas" => $datas]);
    }

    /**
     * サービスレコードの時間更新
     */
    public function updateDiarieItemTime(Request $request){
        $validate_rule = [
            'id' => 'required',
            'time' => 'required|date_format:H:i'
        ];
        $this->validate($request, $validate_rule);

        $diarieItem = DiarieItem::find($request->id);
        $diarieItem->updateTime($request->time);
        // サービス記録編集画面の表示
        return redirect()->back();
    }

    /**
     * 書類の基本情報更新
     */
    public function updateDiarieInfo(Request $request){
        $validate_rule = [
            'id' => 'required',
        ];
        $this->validate($request, $validate_rule);

        if(isset($request->id)){
            // 既存のサービス記録を取得
            $diarie = Diarie::find($request->id);
            if(isset($diarie)){
                $diarie->updateDiarieInfo($request);
            }
        }

        // サービス記録編集画面を表示
        return redirect()->back();
    }

    /**
     * Excelファイル生成
     */
    public function downloadServiceReportFile(Request $request){
        $user = Auth::User();
        $worker = $user->getWorker($request->facilitie_id);
        $facilitie = isset($worker)? $worker->getEmployedFacilitie($worker->facilitie_id) : null;
        $client = $facilitie->getClient($request->client_id);
        $date = $request->date ?: date("Y-m-d");
        $datas = [
            "facilitie" => $facilitie,
            "facilities" => isset($worker)? $worker->getEmployedFacilities() : null,
            "client" => $client,
            "clients" => $facilitie->getClients(),
            "date" => $date,
        ];

        $file = Excel::downloadServiceReportFile($datas);

        // レスポンス
        return response()->download($file[0], $file[1], ['content-type' => 'application/vnd.ms-excel',])->deleteFileAfterSend(true);
    }

    /**
     * 送迎記録出力画面
     */
    public function openTransferReportList(Request $request){
        $user = Auth::User();
        $worker = $user->getWorker($request->facilitie_id);
        $facilitie = isset($worker)? $worker->getEmployedFacilitie($worker->facilitie_id) : null;
        $client = $facilitie->getClient($request->client_id);
        $date = $request->date ?: date("Y-m-d");
        $datas = [
            "title" => "提供実績記録票",
            "facilitie" => $facilitie,
            "facilities" => isset($worker)? $worker->getEmployedFacilities() : null,
            "client" => $client,
            "clients" => $facilitie->getClients(),
            "date" => $date,
            "is_native" => $request->is_native,
        ];

        return view("worker.transfer_report", ["datas" => $datas]);
    }

    /**
     * 提供実績記録票確認ページ
     */
    public function openTransferReportView(Request $request){
        $user = Auth::User();
        $worker = $user->getWorker($request->facilitie_id);
        $facilitie = isset($worker)? $worker->getEmployedFacilitie($worker->facilitie_id) : null;
        $client = $facilitie->getClient($request->client_id);
        $date = $request->date ?: date("Y-m-d");
        $datas = [
            "title" => "提供実績記録票確認",
            "facilitie" => $facilitie,
            "facilities" => isset($worker)? $worker->getEmployedFacilities() : null,
            "client" => $client,
            "date" => $date,
            "is_native" => $request->is_native,
        ];

        $ym = explode("-", $datas["date"]);
        if(!$datas["client"] || !$datas["facilitie"]) exit();
        if(count($ym)<2) exit();

        $datas["diaries"] = Diarie::where("client_id", $client->id)->where("date", ">=", $ym[0]."-".$ym[1])->where("date", "<=", $ym[0]."-".substr("0".($ym[1]+1),-2))->orderBy("date","asc")->get();
        foreach($datas["diaries"] as $diarie){
            $diarie["sign_img"] = SignImage::find($diarie["sign_id"]);
        }
        return view("worker.transfer_report_view", ["datas" => $datas]);
    }

    /**
     * 送迎記録Excelファイル生成
     */
    public function downloadTransferReportFile(Request $request){
        $user = Auth::User();
        $worker = $user->getWorker($request->facilitie_id);
        $facilitie = isset($worker)? $worker->getEmployedFacilitie($worker->facilitie_id) : null;
        $client = $facilitie->getClient($request->client_id);
        $date = $request->date ?: date("Y-m-d");
        $datas = [
            "facilitie" => $facilitie,
            "facilities" => isset($worker)? $worker->getEmployedFacilities() : null,
            "client" => $client,
            "clients" => $facilitie->getClients(),
            "date" => $date,
        ];

        $file = Excel::downloadTransferReportFile($datas);

        // レスポンス
        return response()->download($file[0], $file[1], ['content-type' => 'application/vnd.ms-excel',])->deleteFileAfterSend(true);
    }

    /**
     * 送迎記録
     */
    public function openTransfer(Request $request){
        $user = Auth::User();
        $worker = $user->getWorker($request->facilitie_id);
        $facilitie = isset($worker)? $worker->getEmployedFacilitie($worker->facilitie_id) : null;
        $client = $facilitie->getClient($request->client_id);
        $date = $request->date ?: date("Y-m-d");
        $datas = [
            "title" => "送迎記録入力",
            "facilitie" => $facilitie,
            "facilities" => isset($worker)? $worker->getEmployedFacilities() : null,
            "client" => $client,
            "clients" => $facilitie->getClients(),
            "date" => $date,
            "transfer" => $facilitie->getTransfer($date),
            "is_native" => $request->is_native,
        ];
        return view("worker.transfer_list", ["datas" => $datas]);
    }

    /**
     * サイン画像アップロード
     */
    public function uploadSignImage(Request $request){
        $validate_rule = [
            'client_id' => 'required',
            'facilitie_id' => 'required',
            'sign_img' => 'required'
        ];
        $this->validate($request, $validate_rule);

        $user = Auth::User();
        $worker = $user->getWorker($request->facilitie_id);
        $facilitie = isset($worker)? $worker->getEmployedFacilitie($worker->facilitie_id) : null;

        // サイン画像がアップロードされた場合
        $imageData = base64_decode($request->sign_img);
        $image = imagecreatefromstring($imageData);

        if($image){
            $diarie = Diarie::where("client_id", $request->client_id)->where('date', $request->date)->first();
            $client = Client::find($request->client_id);
            $diarie->setSignImage($facilitie, $request->date, $client, $image);
        }

        return redirect()->back();
    }

    /**
     * 送迎記録更新
     */
    public function updateTransfer(Request $request){
        $validate_rule = [
            'date' => 'required',
            'facilitie_id' => 'required',
            'times' => 'required',
        ];
        $this->validate($request, $validate_rule);

        $date = $request->date;
        $facilitie_id = $request->facilitie_id;
        $worker = Auth::user()->getWorker($request->facilitie_id);
        if(!isset($worker) || $worker["facilitie_id"]!=$facilitie_id) exit();
        $facilitie = isset($worker)? $worker->getEmployedFacilitie($worker->facilitie_id) : null;

        foreach($request->times as $time){
            if(!isset($time["client_id"])) continue;
            $client = Client::find($time["client_id"]);
            if($client["facilitie_id"] != $facilitie->id) continue;
            $diarie = Diarie::where("client_id", $client["id"])->where("date", $date)->first();
            $diarie->updateTransfer($time, $facilitie, $date);
        }
        // レスポンス
        header("Content-type: application/json; charset=UTF-8");
        echo json_encode([
            "date" => $date,
            "facilitie_id" => $facilitie_id
        ]);
        exit();
    }

    /**
     * 職員一覧
     */
    public function openWorkerList(Request $request){
        $user = Auth::User();
        $worker = $user->getWorker($request->facilitie_id);
        $facilitie = isset($worker)? $worker->getEmployedFacilitie($worker->facilitie_id) : null;
        $datas = [
            "title" => "職員一覧",
            "facilitie" => $facilitie,
            "facilities" => isset($worker)? $worker->getEmployedFacilities() : null,
            "workers" => $facilitie->getWorkers(),
            "is_native" => $request->is_native,
        ];

        return view("worker.worker_list", ["datas" => $datas]);
    }

    /**
     * 削除済み職員一覧
     */
    public function openDeletedWorkerList(Request $request){
        $user = Auth::User();
        $worker = $user->getWorker($request->facilitie_id);
        $facilitie = isset($worker)? $worker->getEmployedFacilitie($worker->facilitie_id) : null;
        $datas = [
            "title" => "職員一覧",
            "facilitie" => $facilitie,
            "facilities" => isset($worker)? $worker->getEmployedFacilities() : null,
            "workers" => $facilitie->getWorkers(),
            "deletedworkers" => $facilitie->getDeletedWorkers(),
            "is_native" => $request->is_native,
        ];

        return view("worker.deleted_worker_list", ["datas" => $datas]);
    }

    /**
     * 職員削除
     */
    public function deleteWorker(Request $request){
        $validate_rule = [
            'id' => 'required',
        ];
        $this->validate($request, $validate_rule);

        $user = Auth::user();
        $worker = $user->getWorker($request->facilitie_id);
        if($worker->permit <= 2){
            $worker = Worker::find($request->id);
            $worker->delete();
        }

        return redirect()->back();
    }

    /**
     * 職員復元
     */
    public function restoreWorker(Request $request){
        $validate_rule = [
            'id' => 'required',
        ];
        $this->validate($request, $validate_rule);

        $user = Auth::user();
        $worker = $user->getWorker($request->facilitie_id);
        if($worker->permit <= 2){
            $worker = Worker::find($request->id);
            $worker->restore();
        }

        return redirect()->back();
    }

    /**
     * 職員権限設定
     */
    public function updateWorkerPermit(Request $request){
        $validate_rule = [
            'id' => 'required',
            'facilitie_id' => 'required',
            'permit' => 'required'
        ];
        $this->validate($request, $validate_rule);

        $own = Auth::user()->getWorker($request->facilitie_id);
        $own_facilitie = $own->getEmployedFacilitie($own->facilitie_id);
        $own_facilitie_id = isset($own_facilitie)? $own->facilitie_id : -1;
        $permit = isset($own)? $own->permit : 100;
        // 職員情報を取得
        $worker = Worker::find($request->id);
        $worker_facilitie_id = isset($own_facilitie)? $own_facilitie->id : -2;
        // ユーザID照合
        if($permit <= config('const.WorkerPermit.FACILITIE_ADMIN') && $permit <= $request->permit
           && $own_facilitie_id == $worker_facilitie_id){
            $own_facilitie->updateWorkerPermit($worker, $request->permit);
        }

        // 職員ホーム画面表示
        return redirect('./worker/worker_list?facilitie_id='.$request->facilitie_id);
    }

    /**
     * 職員追加ページ
     */
    public function openWorkerAdd(Request $request){
        $user = Auth::User();
        $worker = $user->getWorker($request->facilitie_id);
        $facilitie = isset($worker)? $worker->getEmployedFacilitie($worker->facilitie_id) : null;
        $datas = [
            "title" => "職員登録",
            "facilitie" => $facilitie,
            "facilities" => isset($worker)? $worker->getEmployedFacilities() : null,
            "is_native" => $request->is_native,
        ];

        // 利用者登録画面を表示
        return view("worker.add_worker", ["datas" => $datas]);
    }

    /**
     * 職員登録
     */
    public function registWorker(Request $request){
        $validate_rule = [
            'facilitie_id' => 'required',
            'name' => 'required',
        ];
        $this->validate($request, $validate_rule);

        $user = Auth::user();
        $worker = $user->getWorker($request->facilitie_id);
        $facilitie = isset($worker)? $worker->getEmployedFacilitie($worker->facilitie_id) : null;

        if(isset($facilitie)){
            $user = null;
            $permit = config('const.WorkerPermit.PART');
            $worker = Worker::regist($facilitie, $user, $request->name, $permit);
            foreach($request->careers as $key => $career_name){
                if($career_name!=""){
                    Career::regist($career_name, $request->get_dates[$key], $worker);
                }
            }
        }
        // 職員一覧画面を表示
        return redirect('./worker/worker_list?facilitie_id='.$request->facilitie_id);
    }

    /**
     * 職員承認画面
     */
    public function openApprovalWorker(Request $request){
        $user = Auth::User();
        $worker = $user->getWorker($request->facilitie_id);
        $facilitie = isset($worker)? $worker->getEmployedFacilitie($worker->facilitie_id) : null;
        $date = $request->date ?: date("Y-m-d");
        $datas = [
            "title" => "新規職員承認",
            "worker" => User::find($request->worker_id),
            "facilitie" => $facilitie,
            "facilities" => [],
            "is_native" => $request->is_native,
        ];

        $facilities = isset($worker)? $worker->getEmployedFacilities() : null;
        // 指定された職員が所属していない施設を選ぶ
        if($facilities){
            $ids = array_column(Worker::where("user_id", $request->worker_id)->get("facilitie_id")->toArray(), "facilitie_id");
            $facilities = $facilities->whereNotIn("id", $ids)->all();

            // 各施設の、アカウントを未登録な職員を探索
            if($facilities){
                foreach($facilities as $facilitie){
                    $datas["facilities"][$facilitie->id] = $facilitie;
                    $datas["facilities"][$facilitie->id]["workers"] = $facilitie->getWorkers();
                }
                return view("worker.approval_worker", ["datas" => $datas]);
            }
        }

        return redirect('./worker_list?facilitie_id='.$request->facilitie_id);
    }

    /**
     * 職員承認
     */
    public function approvalWorker(Request $request){
        $validate_rule = [
            'worker_user_id' => 'required',
            'worker_id' => 'required',
            'facilitie_id' => 'required',
        ];
        $this->validate($request, $validate_rule);

        $user = Auth::User();
        $worker = $user->getWorker($request->facilitie_id);
        $facilitie = isset($worker)? $worker->getEmployedFacilitie($worker->facilitie_id) : null;

        // 雇用情報の各項目にリクエストを代入
        $facilitie->approvalWorker($worker, $request->worker_user_id);

        // ホーム画面へリダイレクト
        return redirect('./worker/worker_list?&facilitie_id='.$request->facilitie_id);
    }

    /**
     * 利用者承認画面
     */
    public function openApprovalChild(Request $request){
        $user = Auth::User();
        $worker = $user->getWorker($request->facilitie_id);
        $facilitie = isset($worker)? $worker->getEmployedFacilitie($worker->facilitie_id) : null;
        $datas = [
            "title" => "保護者承認",
            "worker" => User::find($request->worker_id),
            "facilitie" => $facilitie,
            "facilities" => [],
            "child" => Child::find($request->child_id),
            "is_native" => $request->is_native,
        ];

        $facilities = isset($worker)? $worker->getEmployedFacilities() : null;
        if(!isset($facilities))  return redirect("./home");

        // 各施設の、利用者情報を付加
        foreach($facilities as $facilitie){
            $datas["facilities"][$facilitie->id] = $facilitie;
            $datas["facilities"][$facilitie->id]["clients"] = $facilitie->getClients();
        }
        return view("worker.approval_child", ["datas" => $datas]);
    }

    /**
     * 利用者承認
     */
    public function approvalChild(Request $request){
        $validate_rule = [
            'client_id' => 'required',
            'child_id' => 'required',
        ];
        $this->validate($request, $validate_rule);

        $worker = Auth::user()->getWorker($request->facilitie_id);
        $facilitie = isset($worker)? $worker->getEmployedFacilitie($request->facilitie_id) : null;

        if(!isset($facilitie)) return redirect()->back();

        $child = Child::find($request->child_id);
        $client = $facilitie->getClient($request->client_id);

        if(isset($client) && isset($child)){
            Worker::approvalChild($child, $client);
        }


        // ホーム画面へリダイレクト
        return redirect('./worker/home?&facilitie_id='.$request->facilitie_id);
    }
}
