<style>
    #service-data .date{
        width: 100%;
        display: inline-block;
        text-align: center;
        font-size: 20pt;
        font-weight: 600;
    }
    #service-data .submit-area{
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }
    #service-data .submit-area > *{
        padding: 0 20px;
    }
    #service-data .submit-area > .cname{
        font-size: 15pt;
        color: var(--linkLightBlue);
        border-bottom: solid 1px var(--linkLightBlue);
    }
    #service-data .submit-area > input[type="submit"]{
        color: #fff;
        background-color: var(--linkDarkBlue);
        text-align: center;
        padding: 10px;
    }

    .transfer-table{
        width: 95%;
        margin: 10px auto;
        border: solid 1px #aaa;
    }
    .transfer-table tr > *{
        border: solid 1px #aaa;
    }
    .transfer-table tr > td > img{
        max-height: 30px;
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
        background-color: #fff;
    }
    .ui-area > .form-area > p:first-child{
        border-bottom: solid 1px var(--linkLightBlue);
        padding-left: 10px;
        color: var(--linkDarkBlue);
    }
    .ui-area > .form-area > form input,
    .ui-area > .form-area > form textarea,
    .ui-area > .form-area > form select{
        width: 100%;
        padding: 10px;
        background-color: #FFFFFF;
        border: 1px solid #CCCCCC;
        border-radius: 3px;
    }
    .ui-area > .form-area > form textarea:focus{
        height: 200px;
    }
    .ui-area > .form-area > form input[type="submit"],
    .degital-sign-writer button{
        margin-top: 10px;
        padding: 10px;
        color: #fff;
        background-color: var(--linkDarkBlue);
        text-align: center;
        border: 0;
    }
    .ui-area > .form-area > form input[type="submit"]:hover,
    .img-uploader > .form-area > form label span:hover{
        opacity: 0.6;
    }
    .ui-area > .form-area > form input[name="delete"]{
        background-color: #d33;
    }

    .degital-sign-writer{
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
    .degital-sign-writer > section{
        width: 100%;
        height: 100%;
        padding: 5%;
        font-size: 13pt;
        background-color: #fff;
        overflow-y: scroll;
    }
    .degital-sign-writer .degital-sign-area{
        display: flex;
        flex-direction: column;
    }
    .degital-sign-close {
        margin-bottom: 5px;
        font-weight: bold;
        font-size: 36px;
        text-align: right;
        color: var(--linkDarkBlue);
    }
    .degital-sign-close:hover {
        opacity: 0.6;
        cursor: pointer;
    }
    .degital-sign-writer .degital-sign-area button {
        border-radius: 3px;
    }
    #canvas{
        width: 100%;
        margin-bottom: 5px;
        background-color: #ddd;
    }
    .degital-sign-writer #sign-clear{
        background-color: var(--linkGreen);
    }

    @media screen and (min-width: 1000px) {
        .ui-area > .form-area{    
            width: 70%;
            height: 70%;
            overflow-y: scroll;
            padding: 4%;
            font-size: 13pt;
            background-color: #fff;
        }

        .degital-sign-writer > section {
            width: 70%;
            height: 70%;
            overflow-y: scroll;
            padding: 4% 15%;
            font-size: 13pt;
            background-color: #fff;
        }

        .degital-sign-close {
            display: none;
        }
    }
</style>

<section id="service-data">
    <form method="POST" class="download-form" action='./output_transfer_excel_report'>
        @csrf
        <input type="text" id="monthpicker" class="date" name="date" value="{{ substr($datas["date"], 0, -3) }}" readonly>
        <input type="hidden" name="client_id" value={{ $datas["client"]["id"] ?: -1 }}>
        <input type="hidden" name="facilitie_id" value={{ $datas["facilitie"]["id"] }}>
        <section class="submit-area">
            <span class="cname">{{ $datas["client"]["name"] }}</span>
            <input type="submit" value="Excelで保存">
        </section>
    </form>
</section>
<section class="report-data">
    @php
        $week = ["日","月","火","水","木","金","土"];
    @endphp
    <table class="transfer-table">
        <thead>
            <tr>
                <th rowspan=3>日付</th>
                <th rowspan=3>曜日</th>
                <th colspan=8>サービス提供実績</th>
                <th rowspan=3>保護者等確認印</th>
                <th rowspan=3>備考</th>
            </tr>
            <tr>
                <th rowspan=2>サービス提供の状況</th>
                <th rowspan=2>提供形態</th>
                <th rowspan=2>開始時間</th>
                <th rowspan=2>終了時間</th>
                <th colspan=2>送迎加算</th>
                <th>家庭連携</th>
                <th>訪問支援</th>
            </tr>
            <tr>
                <th>往</th>
                <th>復</th>
                <th>時間数</th>
                <th>時間数</th>
            </tr>
        </thead>
        <tbody>
            @foreach($datas["diaries"] as $diarie)
            @php
                $date = new DateTime($diarie["date"]);
            @endphp
            <tr>
                <td>
                    <input type="hidden" class="diarie-id" value="{{ $diarie["id"] }}">
                    {{ $date->format('d') }}
                </td>
                <td>{{ $week[$date->format('w')] }}</td>
                <td></td>
                <td>{{ $diarie["service_type"] }}</td>
                <td>{{ $diarie["pick_arrive_time"] ?? $diarie["in_time"] }}</td>
                <td>{{ $diarie["drop_depart_time"] ?? $diarie["out_time"] }}</td>
                <td>@if($diarie["pick_driver_id"]!=-1) 1 @endif</td>
                <td>@if($diarie["drop_driver_id"]!=-1) 1 @endif</td>
                <td></td>
                <td></td>
                <td class="degital-sign-triger" onclick="set_date('{{ $diarie["date"] }}')">
                    @if(isset($diarie["sign_img"]))
                    <img src="{{ $diarie["sign_img"]["path"] }}">
                    @endif
                </td>
                <td></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</section>

<!-- 時間・活動内容編集UI -->
<section class="transfer-record ui-area">
    <section class="form-area">
        <p>基本情報</p>
        <form method="POST" action="./update_diarie_info">
            @csrf
            <input type="hidden" name="id">
            <input type="hidden" name="client_id" value="{{ $datas["client"]["id"] }}">
            <input type="hidden" name="facilitie_id" value="{{ $datas["facilitie"]["id"] }}">
            <input type="hidden" name="writer_id" value="{{ Auth::id() }}">
            <input type="hidden" name="date" value="{{ $datas["date"] }}">
            <p><span>サービス提供形態</span>
                <select name="service_type">
                    <option value=0>0:キャンセル
                    <option value=1>1: 放課後</option>
                    <option value=2>2: 日中</option>
                </select>
            </p>
            <p><span>開始時間<br></span>
                <input type="time" name="pick_arrive_time">
            </p>
            <p><span>終了時間<br></span>
                <input type="time" name="drop_depart_time">
            </p>
            <p><span>活動内容</span><textarea name="content"></textarea></p>
            <p><input type="submit" name="save" value="保存"></p>
        </form>
    </section>
</section>

<section class="degital-sign-writer">
    <section class="degital-sign-area">
        <div class="degital-sign-close">&#10005;</div>
        <script src="{{ asset('js/jSignature.min.js') }}"></script>
        <div id="canvas"></div>
        <button id="sign-clear" type="button">クリア</button>
        <button id="sign-save" type="button">保存</button>
    </section>
</section>

<form id="canvas_form" method="post" action="./transfer" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="client_id" value="{{ $datas["client"]["id"] }}">
    <input type="hidden" name="facilitie_id" value="{{ $datas["facilitie"]["id"] }}">
    <input type="hidden" name="date" value="{{ $datas["date"] }}">
    <input type="hidden" name="sign_img" value="">
</form>

<script>
    $("#monthpicker").datepicker({
        dateFormat: 'yy-mm',
        changeMonth: true,
        changeYear: true
    });

    var tmp_date = "{{ $datas["date"] }}";
    // キャンバスをサイン紙化
    let w = $(".degital-sign-area").width();
    let h = $(".degital-sign-area").height();
    var sWidth = w;
    var sHeight = sWidth/2;
    var initFg = false;

    $("#canvas").jSignature({ width: sWidth+"px", height: sHeight+"px", backgroundColor: "#fff" });
    $("#canvas canvas").css("background-color: #fff;");
    // サイン画面を閉じる
    $(".degital-sign-writer").css("display", "none");

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

    // form画面の要素を取得
    let form = (name)=> (".transfer-record > .form-area *[name='"+name+"']");
    let vals = (e, num=0)=> e.currentTarget.children[num].innerText;
    // 値をゼロ埋めした2文字を返す
    let zero = (value)=> ("0"+value).slice(-2);
    // formの各項目名
    let names = ["id", "time", "activity", "comment"];
    let facilitieId = {{ $datas["facilitie"]["id"]}};
    let clientId = {{ $datas["client"]["id"] }};

    // 日付変更時にリダイレクト
    $("#service-data .date").on("change", (e)=>{
        let url = "https://mcs-link.com/worker/transfer_report_view?facilitie_id="+facilitieId+"&client_id="+clientId+"&date="+e.target.value+"-01";
        console.log(url);
        location.href = url;
    });

    // 画面クリック時
    $("#content").on("click", function(e){
        if($(e.target).closest(".degital-sign-triger").length){
            // サインボタンクリックで、サイン画面表示
            //$("#canvas").jSignature("reset"); // サイン画面を開いたとき、内容を初期化
            $(".degital-sign-writer").css("display", "flex");
        }else if($(e.target).closest(".transfer-table tbody tr").length){
            $(form("id")).val(e.currentTarget.querySelector(".diarie-id").value);
            $(form("service_type")).val(vals(e,3));
            $(form("pick_arrive_time")).val(vals(e,4));
            $(form("drop_depart_time")).val(vals(e,5));
            $(".transfer-record").css("display", "flex");
        }else{
            if(!$(e.target).closest(".degital-sign-writer > section").length){
                // サイン画面を閉じる
                $(".degital-sign-writer").css("display", "none");
                client_id = -1;
            }
            if(!$(e.target).closest(".form-area").length){
                $(".ui-area").css("display", "none");
            }
        }
    });

    // バツボタンをクリックしてサイン画面を非表示
    $('.degital-sign-close').click(function() {
        $(".degital-sign-writer").css("display", "none");
    });

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
        form.date.value = tmp_date;
        form.submit();
    });

    function set_date(date){
        tmp_date = date;
    }
</script>
