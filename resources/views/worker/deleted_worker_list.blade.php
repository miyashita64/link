@extends('layouts.worker', ["datas" => $datas])

@section('styles')
<style>
    #content{
        margin: calc(var(--headerHeight) + 10px) 3% 100px;
    }
    #content a{
        color: inherit;
    }

    .profile-area{
        width: 100%;
        height: 100%;
        display: none;
        align-items: center;
        justify-content: center;
        position: fixed;
        top: 0;
        left: 0;
        background-color: rgba(0,0,0,0.5);
    }

    .profile-area form{
        width: 80%;
        height: 80%;
        overflow-y: scroll;
        padding: 3% 5% 8%;
        font-size: 13pt;
        background-color: #fff;
    }
    .profile-area form > *{
        width: 100%;
        display: block;
    }

    .profile-area form > p{
        border-bottom: solid 1px var(--linkLightBlue);
        padding-top: 30px;
        padding-left: 10px;
        color: var(--linkDarkBlue);
    }
    .profile-area form > p > input[type="submit"]{
        display: inline-block;
        height: 30px;
        float: right;
        border: solid 1px var(--linkDarkBlue);
        padding: 0 20px;
        margin-right: 20px;
        margin-bottom: 3px;
    }
    .profile-area form > p > input[type="submit"]:hover{
        color: #fff;
        background-color: var(--linkDarkBlue);
    }
    .profile-area form > *:not(p){
        padding: 0 20px;
    }
    .profile-area form label{
        width: 100%;
        display: flex;
    }
    .profile-area form label > span{
        display: inline-block;
        width: 150px;
    }
    .profile-area form p, .profile-area form select{
        flex-grow: 1;
        border-bottom: solid 1px var(--linkLightBlue);
    }
    .profile-area form label > img{
        width: 150px;
        height: 150px;
        margin: 0 auto;
        padding: 0;
        border: solid 3px #aaa;
        border-radius: 30%;
        object-fit: cover;
    }
</style>
@endsection

@section('content')
<div id="content">
    @php
        $roles = [
            2 => "管理者",
            4 => "共同アカウント",
            5 => "職員",
            10 => "アルバイト"
        ];
        $list_datas = [[
            "url" => "./worker_list?facilitie_id=".$datas["facilitie"]["id"],
            "img_path" => "../img/logo/list.png",
            "title" => "職員一覧に戻る"
        ]];
        foreach($datas["deletedworkers"] as $key => $worker){
            if($worker["user_id"]!=Auth::id()){
                $permit = $roles[$worker["permit"]];
                if(!isset($worker["user"])) $permit .= "(未登録アカウント)";
                $list_datas[] = [
                    "img_path" => isset($worker["user"])? $worker["user"]["icon_path"] : "../img/logo/account.png",
                    "onclick" => "set_profile_data(". $worker["id"] .")",
                    "title" => $worker->getName(),
                    "subtitle" => $permit,
                ];
            }
        }
    @endphp
    @component('components.list',["list_datas" => $list_datas])
    @endcomponent

    <section class="profile-area">
        <form id="worker-info-form" method="POST" action="./restore_worker">
            @csrf
            <p>
                職員のプロフィール
                @if($datas["workers"]->where("user_id", Auth::id())->first()["permit"] <= 2)
                <input type="submit" value="復元">
                @endif
            </p>
            <input type="hidden" name="id">
            <input type="hidden" name="facilitie_id" value="{{ $datas["facilitie"]["id"] }}">
            <label>
                <span>氏名</span>
                <p name="name"></p>
            </label>
            <label>
                <span>携帯番号</span>
                <p name="tel"></p>
            </label>
            <table>
                <thead>
                    <tr><th>資格・経歴</th><th>取得・開始日</th>
                </thead>
                <tbody id="career-list">
                </tbody>
            </table>
        </form>
    </section>

    <script>
        // form画面の要素を取得
        let form = (name)=> ".profile-area > form *[name='"+name+"']";
        // 値をゼロ埋めした2文字を返す
        let zero = (value)=> ("0"+value).slice(-2);

        let workers = @json($datas["deletedworkers"]);
        // 画面クリック時
        $("#content").on("click", function(e){
            // 既存のレコードをクリック時
            if($(e.target).closest(".list-item").length){}
            // 編集画面外をクリック時、編集画面を非表示
            else if(!$(e.target).closest(".profile-area > form").length){
                $(".profile-area").css("display", "none");
            }
        });

        function set_profile_data(id){
            // 表示
            $(".profile-area").css("display", "flex");
            // 既存レコードの値をコピー
            let worker = workers.find((w) => w.id == id);
            console.log(worker);
            $(form("id")).val(worker["id"]);
            $(form("permit")).val(worker["permit"]);
            if(worker["user"]!=null){
                $(form("name")).text(worker["user"]["name"]);
                $(form("tel")).text(worker["user"]["tel"]);
                $(form("icon_path")).attr("src", (worker["user"]["icon_path"]));
                $(form("tel")).css("display", "blcok");
                $(form("icon_path")).css("display", "blcok");
            }else{
                $(form("name")).text(worker["name"]);
                $(form("tel")).text("未登録");
                $(form("icon_path")).css("display", "none");
            }
            let html = "";
            if(worker["careers"]){
                for(let career of worker["careers"]){
                    html += "<tr><td>"+career["name"]+"</td><td>"+career["get_date"]+"</td></tr>"
                }
            }
            if(html=="") html += "<tr><td colspan=2>資格・経歴はありません</td></tr>"
            document.getElementById("career-list").innerHTML = html;
        }
    </script>
</div>
@endsection
