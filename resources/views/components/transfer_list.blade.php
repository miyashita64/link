<section class="transfer-list">
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

        .transfer-list h2{
            padding: 10px;
            text-align: center;
            color: var(--linkDarkBlue);
        }
        .transfer-list button, .transfer-list .update-button{
            width: 100%;
            color: #fff;
            padding: 5px;
            margin: 10px 0;
            text-align: center;
        }
        .transfer-list button:hover, .transfer-list .update-button:hover{
            opacity: 0.5;
        }
        .transfer-list button{
            background-color: var(--linkGreen);
        }
        #sign-save,
        .transfer-list .update-button{
            background-color: var(--linkDarkBlue);
        }
        
        .transfer-list #drop-toggle-form{
            font-size: 0pt;
            margin: 0 auto;
        }
        .transfer-list #drop-toggle-form label{
            width: calc(50% - 2px);
            margin: 0;
            border: solid 1px #ddd;
            text-align: center;
        }
        .transfer-list #drop-toggle-form label input{
            display: none;
        }
        .transfer-list #drop-toggle-form label span{
            display: block;
            width: 100%;
            padding: 5px 0;
            font-size: 15pt;
            color: var(--linkDarkBlue);
            background-color: #fff;
        }
        .transfer-list #drop-toggle-form label input:checked+span{
            color: #fff;
            background-color: var(--linkDarkBlue);
        }
        .transfer-list .drop-toggle-area{
            width: 100%;
            overflow-x: scroll;
        }
        .transfer-list .driver-name{
            color: var(--linkGreen);
            margin-top: 10px;
            padding-left: 20px;
            font-size: 20pt;
            font-weight: 600;
            white-space: nowrap;
            border-bottom: solid 1px #ddd;
        }
        .transfer-list .driver-name+section{
            display: none;
        }
        .transfer-list > .drop-flag{
            border-bottom: solid 1px var(--linkLightBlue);
            padding-left: 10px;
            margin-top: 20px;
            color: var(--linkDarkBlue);
            font-size: 13pt;
        }
        .transfer-list .transfer-table{
            width: 100%;
            border: solid 1px #ddd;
            text-align: center;
        }
        .transfer-list .transfer-table *{
            border-bottom: solid 1px #ddd;
        }

        .transfer-list .degital-sign-writer{
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 15;
            background-color: rgba(0,0,0,0.5);
        }
        .transfer-list .degital-sign-writer > section{
            width: 90%;
            height: 90%;
            padding: 3% 5% 8%;
            font-size: 13pt;
            background-color: #fff;
            overflow-y: scroll;
        }
        .degital-sign-writer .degital-sign-area{
            display: flex;
            flex-direction: column;
        }
        /**/
        #canvas{
            width: 100%;
            background-color: #ddd;
        }

        /*/
        @media (orientation: landscape){
            #canvas{
                width: 100%;
                background-color: #ddd;
            }
        }
        @media (orientation: portrait){
            #canvas *{
                display: none;
            }
            #canvas::before{
                content: "サインを記入するには、画面を横にしてください。";
            }
        }
        /** */
    </style>

    <div id="original_alert"></div>

    @php
        $tmps = [
            ["迎え", "到着", "pick"],
            ["送り", "出発", "drop"],
        ];
        $drivers = [];
        $drivers["pick"] = [];
        $drivers["drop"] = [];
        foreach($datas["transfer"]["trans"] as $key => $trans){
            // 運転手の登録
            $drivers["pick"][$trans["pick_driver"]["name"]] = $key;
            $drivers["drop"][$trans["drop_driver"]["name"]] = $key;
        }
    @endphp

    <h2>送迎記録 <span id="transfer-date">{{ $datas["date"] }}</span></h2>

    <form id="drop-toggle-form">
        @foreach($tmps as $dropFg => $tmp)
        <label>
            <input type="radio" name="dropRadio" value={{ $dropFg }}>
            <span>{{ $tmp[0] }}</span>
        </label>
        @endforeach
    </form>

    @foreach($tmps as $dropFg => $tmp)
    <section class="drop-toggle-area">
    @foreach($drivers[$tmp[2]] as $driver_name => $val)
        <p class="driver-name">{{ $driver_name }}</p>
        <section>
            <table class="transfer-table">
                <thead>
                    <tr>
                        <th>利用者</th>
                        <!-- <th>担当者</th> -->
                        <th>出発</th>
                        <th>到着</th>
                        <th>場所</th>
                        @if($dropFg)
                        <th style="min-width: 30pt; white-space: nowrap;">サイン</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($datas["transfer"]["trans"] as $key => $trans)
                    @if($driver_name != $trans[$tmp[2]."_driver"]["name"])
                        @continue;
                    @endif
                    <tr>
                        <td>{{ $trans["client"]["name"] }}<input type="hidden" name="c_id" value="{{ $trans["client"]["id"] }}"></td>
                        <!-- <td>{{ $trans[$tmp[2]."_driver"]["name"] }}</td> -->
                        <td><input type="time" name="depart_time" value="{{ $trans[$tmp[2]."_depart_time"] }}"></td>
                        <td><input type="time" name="arrive_time" value="{{ $trans[$tmp[2]."_arrive_time"] }}"></td>
                        <td>{{ $trans[$tmp[2]."_addres"] }}</td>
                        @if($dropFg)
                        <td class="degital-sign-triger" onclick='set_id({{ $trans["client"]["id"] }})'>
                            @if($trans["sign_path"]) 済 @else 未 @endif
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <button type="button" class="time-stamp" onclick="timeSet(this, {{ 2-$dropFg }})">{{ $tmp[1] }}</button>
            <button type="button" class="update-button" onclick="send_trans_time(this, {{ $dropFg }})">更新</button>
        </section>
    @endforeach
    </section>
    @endforeach

    <section class="degital-sign-writer">
        <section class="degital-sign-area">
            <script src="{{ asset('js/jSignature.min.js') }}"></script>
            <div id="canvas"></div>
            <button id="sign-clear" type="button">クリア</button>
            <button id="sign-save" type="button">保存</button>
        </section>
    </section>

    <form id="canvas_form" method="post" action="./transfer" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="client_id" value="">
        <input type="hidden" name="facilitie_id" value="{{ $datas["facilitie"]["id"] }}">
        <input type="hidden" name="date" value="{{ $datas["date"] }}">
        <input type="hidden" name="sign_img" value="">
    </form>

    <script src="{{ mix('js/original_alert.js') }}"></script>

    <script>
        // 利用者ID
        var client_id = -1;
        
        // キャンバスをサイン紙化
        let w = $(".degital-sign-area").width();
        let h = $(".degital-sign-area").height();
        //var sWidth = w>h? w : h;
        var sWidth = w;//>h? w : h;
        var sHeight = sWidth/2;
        var initFg = false;

        $("#canvas").jSignature({ width: sWidth+"px", height: sHeight+"px", backgroundColor: "#fff" });
        $("#canvas canvas").css("background-color: #fff;");
        // サイン画面を閉じる
        $(".degital-sign-writer").css("display", "none");

        //*
        let cnv = ($("#canvas canvas"))[0];
        let ctx = cnv.getContext('2d');
        $(window).resize(()=>{
            sWidth = $(".degital-sign-area").width();
            sHeight = sWidth/2;
            
            let img = new Image();
            img.src = cnv.toDataURL();
            img.onload = ()=>{
                $("#canvas").html("");
                $("#canvas").jSignature({ width: sWidth+"px", height: sHeight+"px", backgroundColor: "#fff" });
                cnv = ($("#canvas canvas"))[0];
                ctx = cnv.getContext('2d');
                ctx.drawImage(img, 0, 0, sWidth, sHeight);
            }
        });
        //*/

        // 迎えに初期化
        document.getElementById("drop-toggle-form").dropRadio.value = 0;
        toggle_drop_radio();

        // 送迎切り替え
        $("#drop-toggle-form").on("change", toggle_drop_radio);

        // 画面クリック時
        $("#content").on("click", function(e){
           if($(e.target).closest(".degital-sign-triger").length){
                // サインボタンクリックで、サイン画面表示
                //$("#canvas").jSignature("reset"); // サイン画面を開いたとき、内容を初期化
                $(".degital-sign-writer").css("display", "flex");
           }else if(!$(e.target).closest(".degital-sign-writer > section").length){
                // サイン画面を閉じる
                $(".degital-sign-writer").css("display", "none");
                client_id = -1;
            }
        });

        // 名前クリック時、送迎テーブルの表示・非表示切り替え
        $(".driver-name").on("click", function(e){
            $(e.target).next().toggle(500);
        });

        // 送迎切り替え
        function toggle_drop_radio(){
            let radio = document.getElementById("drop-toggle-form").dropRadio.value;
            $(".drop-toggle-area")[radio].style.display = "block";
            $(".drop-toggle-area")[1-radio].style.display = "none";
        }

        // 利用者IDセット
        function set_id(id){
            client_id = id;
        }

        // 出発ボタンで一括時間入力
        let zero = (value)=> ("0"+value).slice(-2);
        function timeSet(e, key){
            Array.from($($(e).prev().children()[1]).children()).forEach(tr=>{
                let d = new Date();
                tr.children[key].children[0].value = zero(d.getHours())+":"+zero(d.getMinutes());
            });
        }

        // 更新
        function send_trans_time(e, dropFg){
            // var trs = $(".transfer-table:eq("+dropFg+") tbody tr");
            var trs = Array.from($($(e).prev().prev().children()[1]).children());
            var datas = {date: $("#transfer-date")[0].innerText, facilitie_id: {{ $datas["facilitie"]["id"] }},times: []};
            for(let tr of trs){
                if(dropFg){
                    datas.times.push({  client_id: tr.children[0].children[0].value,
                                        drop_depart_time: tr.children[1].children[0].value,
                                        drop_arrive_time: tr.children[2].children[0].value
                    });
                }else{
                    datas.times.push({  client_id: tr.children[0].children[0].value,
                                        pick_depart_time: tr.children[1].children[0].value,
                                        pick_arrive_time: tr.children[2].children[0].value
                    });
                }
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: './transfer_update',
                type: 'POST',
                dateType: 'json',
                data: datas,
                responseType:'arraybuffer',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                timeout: 5000,
            }).done(function(data){
                justAlert("更新しました", "成功");
            }).fail(function(e){
                console.log(e);
                justAlert("更新できませんでした", "失敗");
            });
        }

        // サインクリア
        $("#sign-clear").click(function() {
            $("#canvas").jSignature("reset");
        });
        
        // canvas送信
        $("#sign-save").click(function() {
            // Canvasのデータを取得
            var canvas = $(".jSignature").get(0);
            var image_data = canvas.toDataURL("image/png");
            image_data = image_data.replace(/^.*,/, '');  // DataURI Schemaが返却される

            var form = document.getElementById("canvas_form");
            form.sign_img.value = image_data;
            form.client_id.value = client_id;
            form.submit();
        });

    </script>
</section>