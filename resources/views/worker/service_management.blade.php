@extends('layouts.worker', ["datas" => $datas])

@section('styles')
<style>
    #original_alert {
        width: 90%;
        max-width: 360px;
        margin: 0 auto;
        padding: 10px;
        display: none;
        position: fixed;
        top: 15px;
        left: 0;
        right: 0;
        font-size: 15pt;
        color: #fff;
        background-color: var(--linkDarkBlue);
        border-radius: 3px;
        box-shadow: 0 0 3px 0 #104271;
        z-index: 10;
    }

    #original_alert > span {
        float: right;
    }

    #content{
        margin: calc(var(--headerHeight80pxVer) + 20px) 3% 100px;
    }

    .date{
        width: 100%;
        display: inline-block;
        text-align: center;
        font-size: 20pt;
        font-weight: 600;
    }
    #ui-datepicker-div{
        width: 90%;
    }

    .ui-area{
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
    .ui-area > .form-area{
        width: 80%;
        height: 70%;
        overflow-y: scroll;
        padding: 5%;
        font-size: 13pt;
        background-color: var(--linkWhite);
    }
    .ui-area > .form-area > p:first-child{
        border-bottom: solid 1px var(--linkLightBlue);
        padding-left: 10px;
        color: var(--linkDarkBlue);
    }

    .ui-area > .form-area > form .scheduled-service-time span {
        font-weight: bold;
        color: var(--linkDarkBlue);
    }

    .ui-area > .form-area > form .scheduled-service-time input {
        border-radius: 50px;
        margin-bottom: 5px;
    }

    .ui-area > .form-area > form input, .ui-area > .form-area > form textarea, .ui-area > .form-area > form select{
        width: 100%;
        padding: 10px;
        background-color: #FFFFFF;
        border: 1px solid #CCCCCC;
        border-radius: 3px;
    }
    .ui-area > .form-area > form textarea:focus{
        height: 200px;
    }
    .ui-area > .form-area > form input[type="submit"]{
        margin-top: 10px;
        color: #fff;
        background-color: var(--linkDarkBlue);
        text-align: center;
        border: 0;
    }
    .ui-area > .form-area > form input[type="submit"]:hover, .img-uploader > .form-area > form label span:hover{
        opacity: 0.6;
    }
    .ui-area > .form-area > form .driver_name{
        margin-top: 3px;
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
        $list_datas = [];
        foreach($datas["clients"] as $client){
            $sub = "";
            if($diarie = $datas["diaries"]->where("client_id", $client["id"])->first()){
                $sub .= "形態：". $diarie["service_type"].",迎え担当：";
                $sub .= ($diarie["pick_driver_name"])? $diarie["pick_driver_name"]
                      :(($diarie["pick_driver_id"]<0)? "保護者"
                      :($datas["workers"]->find($diarie["pick_driver_id"])["name"]));
            }else{
                $sub = "未予約";
            }
            $list_datas[] = [
                "onclick" => "set_diarie_data(". $client["id"] .")",
                "img_path" => $client["icon_path"],
                "title" => $client["name"],
                "subtitle" => $sub
            ];
        }
    @endphp

    <div id="original_alert"></div>

    <!-- 日付 -->
    <input type="text" class="date" id="datepicker" value="{{ $datas["date"] }}" readonly>
    @component('components.list', ["list_datas" => $list_datas])
    @endcomponent

    <section class="service-writer ui-area">
        <section class="form-area">
            <p>利用管理</p>
            <form method="POST" action="./service_management">
                @csrf
                <input type="hidden" name="id" value=-1>
                <input type="hidden" name="client_id" value=-1>
                <input type="hidden" name="facilitie_id" value="{{ $datas["facilitie"]["id"] }}">
                <input type="hidden" name="writer_id" value="{{ Auth::id() }}">
                <input type="hidden" name="date" value="{{ $datas["date"] }}">
                <p class="scheduled-service-time">
                    <span>サービス予定時間<br></span>
                    &ensp;開始 :<input type="time" name="in_time">
                    &ensp;終了 :<input type="time" name="out_time">
                </p>
                <p><span>迎え時間</span><input type="time" name="pick_depart_time"></p>
                <p><span>迎え場所</span><input type="text" name="pick_addres"></p>
                <p><span>迎え担当者</span>
                    <select name="pick_driver_id" id="pick_driver_id">
                        <option value="-99" hidden>選択なし</option>
                        <option value="-1">保護者</option>
                        @foreach($datas["workers"] as $worker)
                        <option value="{{ $worker["user_id"] }}">{{ $worker->getName() }}</option>
                        @endforeach
                        <option value="-2">それ以外の人</option>
                    </select>
                    <input type="text" class="driver_name" name="pick_driver_name" id="pick_driver_name" placeholder="名前を入力してください">
                </p>
                <p><span>送り時間</span><input type="time" name="drop_depart_time"></p>
                <p><span>送り場所</span><input type="text" name="drop_addres"></p>
                <p><span>送り担当者</span>
                    <select name="drop_driver_id" id="drop_driver_id">
                        <option value="-99" hidden>選択なし</option>
                        <option value="-1">保護者</option>
                        @foreach($datas["workers"] as $worker)
                        <option value="{{ $worker["user_id"] }}">{{ $worker->getName() }}</option>
                        @endforeach
                        <option value="-2">それ以外の人</option>
                    </select>
                    <input type="text" class="driver_name" name="drop_driver_name" id="drop_driver_name" placeholder="名前を入力してください">
                </p>
                <p><span>サービス提供形態</span>
                    <select name="service_type">
                        <option value=0>0:キャンセル
                        <option value=1 selected>1: 放課後</option>
                        <option value=2>2: 日中</option>
                    </select>
                <p><input type="submit" name="save" value="保存" id="service_writer_submit_button"></p>
            </form>
        </section>
    </section>

    <script src="{{ mix('js/original_alert.js') }}"></script>

    <script>
        $("#datepicker").datepicker({
            dateFormat: 'yy-mm-dd',
        });

        $("#datepicker").on("change",function(e){
            location.assign('./service_management?facilitie_id={{ $datas["facilitie"]["id"] }}&date='+e.target.value);
        });

        let diaries = @json($datas["diaries"]);

        // form画面の要素を取得
        let form = (name)=> ".service-writer > .form-area form *[name='"+name+"']";
        // 値をゼロ埋めした2文字を返す
        let zero = (value)=> ("0"+value).slice(-2);
        // formの各項目
        let cols = [
            { name: "id",               default: -1 },
            { name: "in_time",          default: "" },
            { name: "out_time",         default: "" },
            { name: "pick_depart_time", default: "" },
            { name: "pick_addres",      default: "" },
            { name: "pick_driver_id",   default: -99 },
            { name: "pick_driver_name", default: "" },
            { name: "drop_depart_time", default: "" },
            { name: "drop_addres",      default: "" },
            { name: "drop_driver_id",   default: -99 },
            { name: "drop_driver_name", default: "" },
            { name: "service_type",     default: 1 }
        ];

        // 画面クリック時
        $("#content").on("click", function(e){
            // 編集画面外をクリック時、編集画面を非表示
            if(!$(e.target).closest(".service-writer > .form-area").length && !$(e.target).closest(".list .list-item").length){
                $(".service-writer").css("display", "none");
            }
        });
        // 送迎担当者変更時
        $("#pick_driver_id").on("change", function(e){ change_driver_name(e, "pick"); });
        $("#drop_driver_id").on("change", function(e){ change_driver_name(e, "drop"); });

        function set_diarie_data(id){
            // 既存レコードの値をコピー
            let diarie = diaries.find((d) => d.client_id == id);
            // 編集画面を表示
            $(".service-writer").css("display", "flex");
            $(form("client_id")).val(id);
            if(diarie){
                $.each(cols, (idx,col)=>{
                    $(form(col.name)).val(diarie[col.name]);
                });
            }else{
                $.each(cols, (idx,col)=>{
                    $(form(col.name)).val(col.default);
                });
            }
            $("#pick_driver_id").change();
            $("#drop_driver_id").change();
        }

        function change_driver_name(e, vect){
            let select = e.target;
            let name_input = document.getElementById(vect+"_driver_name");
            let id = e.target.value;

            if(id==-2){
                name_input.style.display = "block";
            }else{
                let name = select.options[select.selectedIndex].innerText;
                name_input.style.display = "none";
                name_input.value = name;
            }
        }

        $('#service_writer_submit_button').click(function() {
            if ($('[name=pick_driver_id] option:selected').text() === "選択なし") {
                justAlert("迎え担当者を選択してください", "失敗");
                return false;
            } else if ($('[name=drop_driver_id] option:selected').text() === "選択なし") {
                justAlert("送り担当者を選択してください", "失敗");
                return false;
            }
        });
    </script>
</div>
@endsection
