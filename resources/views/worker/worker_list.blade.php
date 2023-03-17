@extends('layouts.worker', ["datas" => $datas])

@section('styles')
<style>
    #content{
        margin: calc(var(--headerHeight80pxVer) + 20px) 3% 100px;
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
        height: 70%;
        overflow-y: scroll;
        padding: 5%;
        font-size: 13pt;
        background-color: #fff;
    }
    .profile-area form > *{
        width: 100%;
        display: block;
    }

    .profile-area form > p{
        border-bottom: solid 1px var(--linkLightBlue);
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
    .profile-area form > #delete-button{
        display: inline-block;
        width: 100%;
        padding: 10px;
        margin-top: 20px;
        text-align: center;
        color: #fff;
        background-color: #d33;
    }
    .profile-area form > #delete-button:hover{
        opacity: 0.6;
    }
    .profile-area form label{
        width: 100%;
    }
    .profile-area form label > span{
        font-weight: bold;
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

    @media screen and (min-width: 1000px) {
        #content {
            width: 70%;
            margin-left: 15%;
        }
            
        .ui-area > .form-area{
            width: 70%;
            height: 70%;
            overflow-y: scroll;
            padding: 4%;
            font-size: 13pt;
            background-color: #fff;
        }
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
        $list_datas = [];
        foreach($datas["workers"] as $key => $worker){
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
        $list_datas[] = [
            "url" => "./add_worker?facilitie_id=".$datas["facilitie"]["id"],
            "img_path" => "../img/logo/plus.png",
            "title" => "新規職員登録"
        ];
        if($datas["workers"]->where("user_id", Auth::id())->first()["permit"] <= 2){
            $list_datas[] = [
                "url" => "./deleted_worker_list?facilitie_id=".$datas["facilitie"]["id"],
                "img_path" => "../img/logo/list.png",
                "title" => "削除済み職員復元"
            ];
        }
    @endphp
    @component('components.list',["list_datas" => $list_datas])
    @endcomponent

    <section class="profile-area">
        <form id="worker-info-form" method="POST" action="./update_worker_permit">
            @csrf
            <p>
                職員のプロフィール
                @if($datas["workers"]->where("user_id", Auth::id())->first()["permit"] <= 2)
                <input type="submit" value="更新">
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
            @if($datas["workers"]->where("user_id", Auth::id())->first()["permit"] <= 2)
            <label>
                <span>役職</span>
                <select name="permit">
                    @foreach($roles as $key => $role)
                    @if($datas["workers"]->where("user_id", Auth::id())->first()["permit"] <= $key)
                    <option value="{{ $key }}">{{ $role }}</option>
                    @endif
                    @endforeach
                </select>
            </label>
            @endif
            <table>
                <thead>
                    <tr><th>資格・経歴</th></tr>
                </thead>
                <tbody id="career-list">
                </tbody>
            </table>
            @if($datas["workers"]->where("user_id", Auth::id())->first()["permit"] <= 2)
            <input id="delete-button" type="button" value="削除" onclick="sendDeleteForm()">
            @endif
        </form>
    </section>

    <script>
        // form画面の要素を取得
        let form = (name)=> ".profile-area > form *[name='"+name+"']";
        // 値をゼロ埋めした2文字を返す
        let zero = (value)=> ("0"+value).slice(-2);

        let workers = @json($datas["workers"]);
        // 画面クリック時
        $("#content").on("click", function(e){
            // 既存のレコードをクリック時
            if($(e.target).closest(".list-item").length){}
            // 編集画面外をクリック時、編集画面を非表示
            else if(!$(e.target).closest(".profile-area > form").length){
                $(".profile-area").css("display", "none");
            }
        });

        let names = ["id", "name", "tel", "icon_path", "permit"];

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
                $(form("tel")).css("display", "block");
                $(form("icon_path")).css("display", "block");
            }else{
                $(form("name")).text(worker["name"]);
                $(form("tel")).text("未登録");
                $(form("icon_path")).css("display", "none");
            }
            let html = "";
            if(worker["careers"]){
                for(let career of worker["careers"]){
                    html += '<tr><td><span style="font-weight: bold;">・</span>' + career["name"]+ '<br>【取得･開始日】' + career["get_date"] + '</tr>';
                }
            }
            if(html=="") html += "<tr><td>資格・経歴はありません</td></tr>"
            document.getElementById("career-list").innerHTML = html;
        }

        @if($datas["workers"]->where("user_id", Auth::id())->first()["permit"] <= 2)
        function sendDeleteForm(){
            let form = document.getElementById("worker-info-form");
            form.action = "./deleted_worker";
            if(confirm("本当に削除しますか？\n削除された職員による記録は削除されません。")) form.submit();
        }
        @endif
    </script>
</div>
@endsection
