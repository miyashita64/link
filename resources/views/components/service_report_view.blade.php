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

    .service-document-component{
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        grid-template-rows: repeat(9 ,1fr);
        grid-template-areas: "date date time time time"
                             "col1 type type pick drop"
                             "col2 morn noon uniq uniq"
                             "defi acti acti acti acti"
                             "defi acti acti acti acti"
                             "hydr acti acti acti acti"
                             "hydr acti acti acti acti"
                             "medi acti acti acti acti"
                             "medi acti acti acti acti";
        margin: 0 0 50px;
    }

    .service-document-component > section{
        border-left: solid 1px #aaa;
        border-bottom: solid 1px #aaa;
    }
    .service-document-component > section *{
        margin: 0;
    }
    .service-document-component > .date,
    .service-document-component > .time{
        border-top: solid 1px #aaa;
    }
    .service-document-component > .time,
    .service-document-component > .drop,
    .service-document-component > .uniq,
    .service-document-component > .acti{
        border-right: solid 1px #aaa;
    }
    .service-document-component > .defi > p:first-child,
    .service-document-component > .hydr > p:first-child,
    .service-document-component > .medi > p:first-child,
    .service-document-component > .acti > p:first-child{
        border-bottom: solid 1px #aaa;
    }

    .service-document-component > .date{
        grid-area: date;
    }
    .service-document-component > .time{
        grid-area: time;
    }
    .service-document-component > .col1{
        grid-area: col1;
    }
    .service-document-component > .type{
        grid-area: type;
    }
    .service-document-component > .pick{
        grid-area: pick;
    }
    .service-document-component > .drop{
        grid-area: drop;
    }
    .service-document-component > .col2{
        grid-area: col2;
    }
    .service-document-component > .morn{
        grid-area: morn;
    }
    .service-document-component > .noon{
        grid-area: noon;
    }
    .service-document-component > .uniq{
        grid-area: uniq;
    }
    .service-document-component > .defi{
        grid-area: defi;
    }
    .service-document-component > .hydr{
        grid-area: hydr;
    }
    .service-document-component > .medi{
        grid-area: medi;
    }
    .service-document-component > .acti{
        grid-area: acti;
    }
    .service-document-component > .acti > p:nth-child(2){
        white-space: pre-wrap;
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
    .ui-area > .form-area > form input[name="delete"]{
        background-color: #d33;
    }

    .ui-area > .form-area > form .time-inputs span:first-child {
        font-weight: bold;
        color: var(--linkDarkBlue);
    }

    .ui-area > .form-area > form .time-inputs input {
        border-radius: 50px;
        margin-bottom: 5px;
    }

    .service-record > .form-area .record-items{
        display: flex;
        flex-direction: column;
    }
    .service-record > .form-area .record-items > p{
        border-bottom: solid 1px var(--linkLightBlue);
        padding-left: 10px;
        margin: 10px 0;
        color: var(--linkDarkBlue);
    }
    .service-record > .form-area .record-items > .record-item{
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: none;
    }
    .service-record > .form-area .record-items > .record-item > *{
        padding: 15px 0;
        border: solid 3px #fff;
    }
    .service-record > .form-area .record-items > .record-item > input[type="text"]{
        max-width: 80px;
        text-align: center;
    }
    .service-record > .form-area .record-items > .record-item > input[type="time"]{
        flex-grow: 1;
        flex-shrink: 1;
        margin: 3px;
        padding-left: 5px;
        padding-right: 5px;
        background-color: #FFFFFF;
        border: 1px solid #CCCCCC;
        border-radius: 3px;
    }
    .service-record > .form-area .record-items > .record-item > input[type="button"]{
        width: 80px;
        text-align: center;
        color: #fff;
        background-color: var(--linkDarkBlue);
        border-radius: 6px;
    }
    .service-record > .form-area .record-items > .record-item > input[type="button"].delete-button{
        background-color: #d33;
    }
    .service-record > .form-area .record-items > .record-item > input[type="button"]:hover{
        opacity: 0.6;
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
    }
</style>

<section id="service-data">
    <form method="POST" class="download-form" action='./output_excel_report'>
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
<section class="report-list">
    @foreach($datas["diaries"] as $diarie)
    <section class="service-document-component">
        <input type="hidden" class="diarie-id" value="{{ $diarie["id"] }}">
        <section class="date">サービス提供日：<span class="val">{{ $diarie["date"] }}</span></section>
        @if($diarie["service_type"]==0)
        <section class="time">キャンセル</section>
        @else
        <section class="time">サービス提供時間：
            <span class="val">{{ substr($diarie["pick_arrive_time"], 0, -3) }}</span>
            ~<span class="val">{{ substr($diarie["drop_depart_time"], 0, -3) }}</span>
        </section>
        <section class="col1">送迎記録</section>
        <section class="type">提供形態 <span class="val">{{ $diarie["service_type"] }}</span></section>
        <section class="pick text-form">
            <span>迎え：
                <span class="val">{{ substr($diarie["pick_depart_time"], 0, -3) }}</span>
                ~<span class="val">{{ substr($diarie["pick_arrive_time"], 0, -3) }}</span>
            </span>
        </section>
        <section class="drop text-form">
            <span>送り：
                <span class="val">{{ substr($diarie["drop_depart_time"], 0, -3) }}</span>
                ~<span class="val">{{ substr($diarie["drop_arrive_time"], 0, -3) }}</span>
            </span>
        </section>
        <section class="col2">今日の活動</section>
        <section class="morn text-form"><span>午前：</span></section>
        <section class="noon text-form"><span>午後：</span></section>
        <section class="uniq text-form"><span>個別：</span></section>
        <section class="defi">
            <p>排尿・排便</p>
            <section class="vals">
            @foreach($diarie["defication"] as $item)
                <p class="record-item">
                    <input class="diarie-item-id" type="hidden" value="{{ $item["id"] }}">
                    <span class="val">{{ substr($item["time"], -8, 5)." " }}</span>
                </p>
            @endforeach
            </section>
        </section>
        <section class="hydr">
            <p>水分補給</p>
            <section class="vals">
            @foreach($diarie["hydration"] as $item)
                <p class="record-item">
                    <input class="diarie-item-id" type="hidden" value="{{ $item["id"] }}">
                    <span class="val">{{ substr($item["time"], -8, 5)." " }}</span>
                </p>
            @endforeach
            </section>
        </section>
        <section class="medi">
            <p>服薬</p>
            <section class="vals">
            @foreach($diarie["medication"] as $item)
                <p class="record-item">
                    <input class="diarie-item-id" type="hidden" value="{{ $item["id"] }}">
                    <span class="val">{{ substr($item["time"], -8, 5)." " }}</span>
                </p>
            @endforeach
            </section>
        </section>
        <section class="acti">
            <p>活動内容</p>
            <p class="val">{{ $diarie["content"] }}</p>
        </section>
        @endif
    </section>
    @endforeach
</section>

<!-- レコード編集UI -->
<section class="service-record ui-area">
    <section class="form-area"></section>
</section>

<!-- 時間・活動内容編集UI -->
<section class="service-management ui-area">
    <section class="form-area">
        <p>基本情報</p>
        <form method="POST" action="./update_diarie_info">
            @csrf
            <input type="hidden" name="id">
            <input type="hidden" name="client_id" value="{{ $datas["client"]["id"] }}">
            <input type="hidden" name="facilitie_id" value="{{ $datas["facilitie"]["id"] }}">
            <input type="hidden" name="writer_id" value="{{ Auth::id() }}">
            <input type="hidden" name="date" value="{{ $datas["date"] }}">
            <p class="time-inputs">
                <span>サービス提供時間<br></span>
                <span>※「迎え到着～送り出発」の時間を使用します</span>
                <input type="hidden" name="in_time">
                <input type="hidden" name="out_time">
            </p>
            <p>
                <span>サービス提供形態</span>
                <select name="service_type">
                    <option value=0>0:キャンセル
                    <option value=1>1: 放課後</option>
                    <option value=2>2: 日中</option>
                </select>
            </p>
            <p class="time-inputs">
                <span>迎え<br></span>
                出発時間 :<input type="time" name="pick_depart_time">
                到着時間 :<input type="time" name="pick_arrive_time">
            </p>
            <p class="time-inputs">
                <span>送り<br></span>
                出発時間 :<input type="time" name="drop_depart_time">
                到着時間 :<input type="time" name="drop_arrive_time">
            </p>
            <p><span>活動内容</span><textarea name="content"></textarea></p>
            <p><input type="submit" name="save" value="保存"></p>
        </form>
    </section>
</section>

<script>
    $("#monthpicker").datepicker({
        dateFormat: 'yy-mm',
        changeMonth: true,
        changeYear: true
    });

    // form画面の要素を取得
    let form = (name)=> ".service-management > .form-area *[name='"+name+"']";
    let vals = (e, name, num=0)=> e.currentTarget.querySelectorAll("."+name+" .val")[num].innerText;
    // 値をゼロ埋めした2文字を返す
    let zero = (value)=> ("0"+value).slice(-2);
    // formの各項目名
    let names = ["id", "time", "activity", "comment"];
    let facilitieId = {{ $datas["facilitie"]["id"]}};
    let clientId = {{ $datas["client"]["id"] }};

    // 日付変更時にリダイレクト
    $("#service-data .date").on("change", (e)=>{
        let url = "https://mcs-link.com/worker/service_report_view?facilitie_id="+facilitieId+"&client_id="+clientId+"&date="+e.target.value+"-01";
        console.log(url);
        location.href = url;
    });
    // UI画面表示の呼び出し設定
    $(".service-document-component").on("click", (e)=>{
        if($(e.target).closest(".defi").length){ flex_record(e,"排泄"); }
        else if($(e.target).closest(".hydr").length){ flex_record(e,"水分補給"); }
        else if($(e.target).closest(".medi").length){ flex_record(e,"服薬"); }
        else{ flex_management(e); }
    });
    // UI画面非表示の呼び出し設定
    $(".ui-area").on("click", (e)=>{
        if(!$(e.target).closest(".form-area").length){ $(".ui-area").css("display", "none"); }
    });

    // レコード編集UIの表示
    function flex_record(e,type){
        let record = document.querySelector(".service-record");
        let diarieId = e.currentTarget.querySelector(".diarie-id").value;
        record.style.display = "flex";
        $(".service-record select[name='activity']").val(type);
        let html = '<p>'+type+'記録</p>'
                 + '<section class="record-items">';
        let recordType = (type=="排泄")? "defi" : (type=="水分補給")? "hydr" : (type=="服薬")? "medi" : "error";
        for(let item of e.currentTarget.querySelectorAll("."+recordType+" .vals .record-item")){
            html += '<form method="POST" class="record-item">'
                  + '   <input type="hidden" name="_token" value="'+$('meta[name="csrf-token"]').attr('content')+'">'
                  + '   <input type="hidden" name="id" value="'+item.querySelector(".diarie-item-id").value+'">'
                  + '   <input type="time" name="time" value="'+item.querySelector(".val").innerText+'">'
                  + '   <input type="button" class="save-button" value="保存" onclick="update_diarie_item_time(this)">'
                  + '   <input type="button" class="delete-button" value="削除" onclick="delete_diarie_item(this)">'
                  + '</form>';
        }
        let now = new Date();
        html += '<p>'+type+'記録追加</p>'
              + '<form method="POST" class="record-item">'
              + '   <input type="hidden" name="_token" value="'+$('meta[name="csrf-token"]').attr('content')+'">'
              + '   <input type="hidden" name="id" value="-1">'
              + '   <input type="hidden" name="diarie_id" value="'+diarieId+'">'
              + '   <input type="text" name="activity" value="'+type+'" readonly>'
              + '   <input type="time" name="time" value="'+zero(now.getHours())+':'+zero(now.getMinutes())+'">'
              + '   <input type="button" value="追加" onclick="create_diarie_item(this)">'
              + '</form>'
        html += '</section>';
        record.querySelector(".form-area").innerHTML = html;
    }
    // 基本情報編集UIの表示
    function flex_management(e){
        try{
            $(form("id")).val(e.currentTarget.querySelector(".diarie-id").value);
            $(form("service_type")).val(vals(e,"type"));
            $(form("in_time")).val(vals(e,"time"));
            $(form("out_time")).val(vals(e,"time",1));
            $(form("pick_depart_time")).val(vals(e,"pick"));
            $(form("pick_arrive_time")).val(vals(e,"pick",1));
            $(form("drop_depart_time")).val(vals(e,"drop"));
            $(form("drop_arrive_time")).val(vals(e,"drop",1));
            $(form("content")).val(vals(e,"acti"));
            $(".service-management").css("display", "flex");
        }catch(error){}
    }

    // レコード保存(更新)ボタンクリック時の処理
    function update_diarie_item_time(target){
        let form = target.parentNode;
        form.action = "./update_diarie_item_time";
        form.submit();
    }
    // レコード削除ボタンクリック時の処理
    function delete_diarie_item(target){
        let form = target.parentNode;
        form.action = "./delete_diarie_item";
        form.submit();
    }
    // レコード追加ボタンクリック時の処理
    function create_diarie_item(target){
        let form = target.parentNode;
        form.action = "./edit_diarie";
        form.submit();
    }
</script>
