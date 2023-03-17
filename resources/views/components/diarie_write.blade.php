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
    .daily{
        border: solid 5px var(--linkLightBlue);
        border-radius: 10px;
        margin: 10px 5%;
        padding: 10px;
        position: relative;
    }
    .daily .cname{
        font-size: 15pt;
        color: var(--linkLightBlue);
        text-decoration: underline;
        margin-top: 20px;
    }
    .daily .pdf-view{
        display: inline-block;
        position: absolute;
        top: auto;
        right: 5%;
    }
    .daily table{
        width: 100%;
        background-color: #eee;
        text-align: center;
    }
    .daily table tr{
        border-bottom: solid 2px #fff;
    }
    .daily table th{
        white-space: nowrap;
    }
    .daily table td{
        border-right: solid 1px #fff;
    }
    .daily table td:nth-child(2){
        min-width: 80px;
    }

    .nothing-msg{
        font-size: 15pt;
        text-align: center;
    }

    .before-button, .next-button{
        width: 10%;
        position: fixed;
        top: 45%;
    }
    .before-button > img, .next-button > img{
        width: 100%;
    }
    .before-button{ left: 0; }
    .next-button{ right: 0; }

    .controll-area{
        width: 90%;
        margin: auto 5%;
        display: flex;
        justify-content: space-between;
    }
    .controll-area > button{
        flex-grow: 1;
        margin: 1%;
        border: solid 5px var(--linkLightBlue);
        border-radius: 25%;
        text-align: center;
    }
    .controll-area > button > img{
        width: 80%;
        padding: 10%;
    }
    .controll-area > button > img:hover{
        opacity: 0.6;
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
    .ui-area-invisible-background {
        background-color: rgba(0,0,0,0);
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
    .ui-area > .form-area > form input, .ui-area > .form-area > form textarea{
        width: 100%;
        padding: 10px;
        background-color: #FFFFFF;
        border: 1px solid #CCCCCC;
        border-radius: 3px;
    }
    .ui-area > .form-area > form input[type="submit"] {
        border: 0;
    }
    .ui-area > .form-area > form textarea:focus{
        height: 200px;
    }
    .document-writer > .form-area > form p:nth-of-type(1) > textarea{
        background-color: #def;
        border: solid 1px #00f;
    }
    .document-writer > .form-area > form p:nth-of-type(2) > textarea{
        background-color: #fdd;
        border: solid 1px #f00;
    }
    .document-writer > .form-area > form p:nth-of-type(3) > textarea{
        background-color: #dfe;
        border: solid 1px #090;
    }
    .ui-area > .form-area > form input[type="submit"],
    .ui-area > .form-area > #img-upload-form input[type="button"]{
        margin-top: 10px;
        color: #fff;
        background-color: var(--linkDarkBlue);
        text-align: center;
        border-radius: 3px;
    }
    .ui-area > .form-area > form input[type="submit"]:hover,
    .img-uploader > .form-area label span:hover,
    .ui-area > .form-area > #img-upload-form input[type="button"]:hover{
        opacity: 0.6;
    }
    .daily-writer > .form-area > form input[name="delete"]{
        background-color: #d33;
    }

    .hide-input {
        margin: 0;
    }

    .daily-writer form p:last-child {
        margin: 0;
    }

    .daily-writer form p:last-child input[type="submit"] {
        margin: 0;
        margin-bottom: 10px;
    }

    .img-uploader .img-list{
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
    }
    .img-uploader .img-list > img{
        width: 100%;
        margin-bottom: 10px;
        object-fit: cover;
    }
    .img-uploader > .form-area label {
        text-align: left;
        margin-left: 5px;
    }
    .img-uploader > .form-area label > img {
            width: 50px;
            height: 50px;
            margin-right: 5px;
            border: solid 3px #aaa;
            border-radius: 30%;
        }
    .img-uploader > .form-area label span{
        line-height: 50px;
        color: var(--linkGreen);
    }
    .img-uploader > .form-area label span:hover {
        opacity: 1;
    }
    .img-uploader > .form-area form > input[type="button"] {
        border: 0;
    }
    .img-uploader .upload-images-view > img{
        width: 100%;
        margin-top: 10px;
    }

    .img-viewer > img{
        width: 80%;
        height: 70%;
        padding: 2%;
        background-color: #fff;
        object-fit: contain;
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

        .img-uploader .img-list > img{
            width: 50%;
            object-fit: cover;
        }

        .img-uploader > .form-area label {
            text-align: center;
            margin-left: 0;
        }

        .img-viewer > img{
            width: 70%;
            height: 70%;
            padding: 2%;
            background-color: #fff;
            object-fit: contain;
        }
    }
</style>

<div id="original_alert"></div>

<!-- 日付変更ボタン -->
<a href="./edit_diarie?facilitie_id={{ $datas["facilitie"]["id"] }}&client_id={{ $datas["client"]["id"] }}&date={{date('Y-m-d',strtotime("$datas[date]"." -1 day"))}}" class="before-button"><img src="../img/logo/left.png"></a>
<a href="./edit_diarie?facilitie_id={{ $datas["facilitie"]["id"] }}&client_id={{ $datas["client"]["id"] }}&date={{date('Y-m-d',strtotime("$datas[date]"." 1 day"))}}" class="next-button"><img src="../img/logo/right.png"></a>

@if($datas["diarie"]==null)
@if($datas["date"])
<input type="text" class="date" id="datepicker" value="{{ $datas["date"] }}" readonly>
@endif
<p class="daily" style="text-align: center;">本日の利用予定はありません</p>
@else

<!-- レコード入力項目 -->
<datalist id="items">
    @foreach($datas["items"] as $item)
    <option value={{$item}}></option>
    @endforeach
</datalist>

<!-- 記入済みレコード表示 -->
<section class="diarie-section">
    <!-- 日付 -->
    <input type="text" class="date" id="datepicker" value="{{ $datas["date"] }}" readonly>
    <!-- 連絡帳閲覧テーブル -->
    <section class="daily">
        <!-- 利用者名 -->
        <span class="cname">{{ $datas["clients"]->find($datas["client"]["id"])["name"] }}</span>
        <span class="pdf-view"><a href="./pdf?facilitie_id={{ $datas["facilitie"]["id"] }}&client_id={{ $datas["client"]["id"] }}&date={{ $datas["date"] }}">PDFをダウンロード</a></span>

        <!-- 既存のレコード表示欄 -->
        @if($datas["diarie"])
        <table>
            <thead>
                <tr><th>時間</th><th>支援内容</th><th>様子</th><th>非表示</th></tr>
            </thead>
            <!-- 既存のレコード -->
            <tbody class="viewer">
                @foreach($datas["diarie"]["items"] as $event)
                <tr>
                    <input type="hidden" name="item_id" value="{{ $event["id"] }}">
                    <td>{{ $event["time"]->format('H:i') }}</td>
                    <td>{{ $event["activity"] }}</td>
                    <td>{{ $event["comment"] }}</td>
                    <td>{{ [" ", "非"][$event->parent_hidden] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <!-- 個人へのメッセージ -->
        <span><strong>保護者へのコメント</strong></span>
        <p>{{ $datas["diarie"]["private_msg"] }}</p>
    </section>

    <section class="daily">
        <span><strong>施設内共有情報(保護者には送信されません)</strong></span>
        <p>{{ $datas["diarie"]["hidden_msg"] }}</p>
        <span><strong>支援内容(書類に反映)</strong></span>
        <p style="white-space: pre-wrap;">{{ $datas["diarie"]["content"] }}</p>
        @endif
    </section>
</section>

<!-- UI -->
<section class="controll-area">
    <!-- 画像追加ボタン -->
    <button type="button" class="upload-img"><img src="../img/logo/image.png"></button>
    <!-- レコード追加ボタン -->
    <button type="button" class="edit-item"><img src="../img/logo/add_list.png"></button>
    <!-- 書類記入ボタン -->
    <button type="button" class="edit-document"><img src="../img/logo/edit_document.png"></button>
</section>

<!-- 画像アップロードUI -->
<section class="img-uploader ui-area">
    <section class="form-area">
        <p>活動写真</p>
        <!-- 既存の活動写真 -->
        <section class="img-list">
            @foreach($datas["active_imgs"] as $img)
            <img src="{{ $img["path"] }}">
            @endforeach
        </section>
        <!-- 画像追加・編集用フォーム -->
        <label style="display: block;">
            <img src="../img/logo/plus.png"><span>追加画像選択</span>
            <input type="file" name="original_active_imgs[]" style="display: none;" accept="image/*" multiple>
            <section class="upload-images-view"></section>
        </label>
        <form enctype="multipart/form-data" id="img-upload-form">
            @csrf
            <input type="hidden" name="diarie_id" value="{{ $datas["diarie"]? $datas["diarie"]["id"] : -1 }}">
            <input type="hidden" name="client_id" value="{{ $datas["client"]["id"] }}">
            <input type="hidden" name="facilitie_id" value="{{ $datas["facilitie"]["id"] }}">
            <input type="hidden" name="date" value="{{ $datas["date"] }}">
            <!--
            <input type="file" name="active_imgs[]" style="display: none;" accept="image/*" multiple>
            -->
            <input type="button" value="追加" onclick="ActiveImageUpLoad()">
        </form>
    </section>
</section>

<!-- 画像表示用UI -->
<section class="img-viewer ui-area ui-area-invisible-background" style="z-index: 1;">
    <img>
</section>

<!-- レコード編集UI -->
<section class="daily-writer ui-area">
    <section class="form-area">
        <p>記録登録</p>
        <!-- レコード追加・編集用フォーム -->
        <form method="POST" action="./edit_diarie">
            @csrf
            <input type="hidden" name="diarie_id" value="{{ $datas["diarie"]? $datas["diarie"]["id"] : -1 }}">
            <input type="hidden" name="id" value=-1>
            <input type="hidden" name="client_id" value="{{ $datas["client"]["id"] }}">
            <input type="hidden" name="facilitie_id" value="{{ $datas["facilitie"]["id"] }}">
            <input type="hidden" name="date" value="{{ $datas["date"] }}">
            <p><span>時間</span><input type="time" name="time" value="{{ (new DateTime('now'))->format('H:i') }}"></p>
            <p><span>支援内容</span><input type="text" name="activity" list="items" autocomplete="off"></p>
            <p><span>様子</span><textarea name="comment"></textarea></p>
            <p>
                <label class="hide-input">
                    <input type="checkbox" name="hidden" style="width: 20px;">
                    <span>非表示にする</span>
                </label>
            </p>
            <p><input type="submit" name="update" value="追加"></p>
        </form>
        <!-- レコード削除用フォーム -->
        <form method="POST" action="./delete_diarie_item">
            @csrf
            <input type="hidden" name="id">
            <input type="hidden" name="client_id" value="{{ $datas["client"]["id"] }}">
            <input type="hidden" name="facilitie_id" value="{{ $datas["facilitie"]["id"] }}">
            <input type="hidden" name="date" value="{{ $datas["date"] }}">
            <p><input type="submit" name="delete" value="削除"></p>
        </form>
    </section>
</section>

<!-- 書類記入UI -->
<section class="document-writer ui-area">
    <section class="form-area">
        <p>コメント・共有情報</p>
        <!-- 書類記入フォーム -->
        <form method="POST" action="./edit_document">
            @csrf
            <input type="hidden" name="id" value="{{ $datas["diarie"]? $datas["diarie"]["id"] : -1 }}">
            <input type="hidden" name="client_id" value="{{ $datas["client"]["id"] }}">
            <input type="hidden" name="facilitie_id" value="{{ $datas["facilitie"]["id"] }}">
            <input type="hidden" name="writer_id" value="{{ Auth::id() }}">
            <input type="hidden" name="date" value="{{ $datas["date"] }}">

            <p><span>保護者へのコメント</span><textarea name="private_msg">{{ $datas["diarie"]? $datas["diarie"]["private_msg"] : "" }}</textarea></p>
            <p><span>施設内共有情報</span><textarea name="hidden_msg">{{ $datas["diarie"]? $datas["diarie"]["hidden_msg"] : "" }}</textarea></p>
            <p><span>支援内容(書類に反映)</span><textarea name="content">{{ $datas["diarie"]? $datas["diarie"]["content"] : "" }}</textarea></p>

            <p><input type="submit" name="save" value="保存"></p>
        </form>
    </section>
</section>

<script src="{{ mix('js/original_alert.js') }}"></script>

<script>
    // form画面の要素を取得
    let form = (name)=> ".daily-writer > .form-area *[name='"+name+"']";
    // 値をゼロ埋めした2文字を返す
    let zero = (value)=> ("0"+value).slice(-2);
    // formの各項目名
    let names = ["id", "time", "activity", "comment", "parent_hidden"];

    // 活動写真送信用フォームデータ
    var imgFormData = new FormData(document.getElementById("img-upload-form"));

    // 画面クリック時
    $("#content").on("click", function(e){
        // 画像追加ボタンクリックで、画像アップロード画面表示
        if($(e.target).closest(".controll-area .upload-img").length){
            // アップロード画面を表示
            $(".img-uploader").css("display", "flex");
        }
        // 画像アップロード画面上の画像クリックで、拡大表示
        else if($(e.target).closest(".img-uploader .img-list > img").length){
            $(".img-viewer > img").attr("src", e.target.src);
            $(".img-viewer").css("display", "flex");
        }
        // 拡大表示した画像を非表示
        else if($(".img-viewer").css("display")!="none"){
            if(!$(e.target).closest(".img-viewer > img").length){
                $(".img-viewer").css("display", "none");
            }
        }
        // アップロード画面外をクリック時、アップロード画面を非表示
        else if(!$(e.target).closest(".img-uploader > .form-area").length){
            $(".img-uploader").css("display", "none");
        }

        // レコード追加ボタンクリックで、編集画面を初期化し表示
        if($(e.target).closest(".controll-area .edit-item").length){
            // 各項目の値を初期化
            $.each(names, (idx,name)=>{
                if(name=="id"){ $(form(name)).val(-1); }
                else if(name=="time"){ let d = new Date(); $(form(name)).val(zero(d.getHours())+":"+zero(d.getMinutes())); }
                else if(name=="parent_hidden"){ $(form(name)).prop("checked", false); }
                else{ $(form(name)).val(""); }
            });
            // 送信ボタンのメッセージを"追加"に変更
            $(form("update")).val("追加");
            // 削除ボタンの非表示
            $(form("delete")).css("display", "none");
            // 編集画面を表示
            $(".daily-writer").css("display", "flex");
        }
        // 既存のレコードをクリック時、編集画面表示
        else if($(e.target).closest(".viewer tr").length){
            // 既存レコードの値をコピー
            $target = $(e.target).closest(".viewer tr");
            $.each(names, (idx,name)=>{
                if(name=="id"){
                    $(form(name)).val($target.find('input').val());
                }else if(name=="parent_hidden"){
                    let hide = $target.find('td').text().slice(-1);
                    $(form(name)).prop("checked", (hide=="非")? true : false);
                }else{
                    $(form(name)).val($target.children('td')[idx-1].innerText);
                }
            });
            // 送信ボタンのメッセージを"更新"に変更
            $(form("update")).val("更新");
            // 削除ボタンの表示
            $(form("delete")).css("display", "inline-block");
            // 編集画面を表示
            $(".daily-writer").css("display", "flex");
        }
        // 編集画面外をクリック時、編集画面を非表示
        else if(!$(e.target).closest(".daily-writer > .form-area").length){
            $(".daily-writer").css("display", "none");
        }

        // 書類記入ボタンクリックで、編集画面表示
        if($(e.target).closest(".controll-area .edit-document").length){
            // 記入画面を表示
            $(".document-writer").css("display", "flex");
        }
        // 編集画面外をクリック時、記入画面を非表示
        else if(!$(e.target).closest(".document-writer > .form-area").length){
            $(".document-writer").css("display", "none");
        }
    });

    // アップロード画像選択時
    $('.img-uploader input[name="original_active_imgs[]"]').on('change', function(e){
        e.target.nextElementSibling.innerHTML = "";
        for(let file of e.target.files){
            var reader = new FileReader();
            var smallImages = [];
            reader.onload = function(e2){
                // 選択された画像を表示
                e.target.nextElementSibling.innerHTML += "<img src=" + e2.target.result + ">";
                // 以下、画像処理
                var image = new Image();
                image.src = e2.target.result
                image.onload = ()=>{
                    const maxEdge = 800;
                    let width = image.width;
                    let height = image.height;
                    let canvas = document.createElement('canvas');
                    // サイズ調整
                    if(width*height > maxEdge*maxEdge){
                        if(width > height){
                            height *= maxEdge / width;
                            width = maxEdge;
                        }else{
                            width *= maxEdge / height;
                            height = maxEdge;
                        }
                    }
                    canvas.width = width;
                    canvas.height = height;
                    let ctx = canvas.getContext('2d');
                    ctx.drawImage(image, 0, 0, width, height);
                    ctx.canvas.toBlob((blob) => {
                        const imageFile = new File([blob], file.name, {
                            type: file.type,
                            lastModified: Date.now()
                        });
                        smallImages.push(imageFile);

                        imgFormData.append("active_imgs[]", imageFile);
                    }, file.type, 1);
                };
            }
            reader.readAsDataURL(file);
        }
    });

    function ActiveImageUpLoad(){
        $.ajax({
            async: false,
            type: "POST",
            url: "./upload_img",
            data: imgFormData,
            dataType: "text",
            cache: false,
            contentType: false,
            processData: false,
            error: function (XMLHttpRequest) {
                console.log(XMLHttpRequest);
                justAlert("アップロードに失敗しました", "失敗");
            },
            success: function (res) {
                location.reload();
            }
        });
    }
</script>

@endif

<script>
    $("#datepicker").datepicker({
        dateFormat: 'yy-mm-dd',
    });

    $("#datepicker").on("change", function(e) {
        location.assign('./edit_diarie?facilitie_id={{ $datas["facilitie"]["id"] }}&client_id={{ $datas["client"]["id"] }}&date=' + e.target.value);
    });
</script>