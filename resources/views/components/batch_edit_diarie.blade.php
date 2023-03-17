<section class="batch-edit-diarie">
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
            z-index: 1;
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
        .ui-area > .form-area label{
            width: 100%;
        }
        .ui-area > .form-area input:not([type="checkbox"]),
        .ui-area > .form-area textarea{
            width: 100%;
            padding: 10px;
            background-color: #eee;
        }
        .ui-area > .form-area input[type="button"]{
            color: #fff;
            background-color: var(--linkDarkBlue);
            text-align: center;
            margin-top: 20px;
            border-radius: 3px;
        }

        #item-delete-button{
            background-color: #d33;
        }

        #exist-item-area,
        #batch-data-form{
            border: solid 5px var(--linkLightBlue);
            border-radius: 10px;
            margin: 10px auto;
            padding: 10px;
        }
        #exist-item-table{
            width: 100%;
            text-align: center;
            background-color: #eee;
        }
        #exist-item-table tr{
            border-bottom: solid 2px #fff;
        }
        #exist-item-table th{
            white-space: nowrap;
        }
        #exist-item-table td{
            border-right: solid 1px #fff;
        }
        #exist-item-table td:nth-child(2){
            min-width: 80px;
        }

        #batch-data-form > .button-area{
            width: 100%;
            display: flex;
            justify-content: space-around;
        }
        #batch-data-form > .button-area > input[type="button"]{
            background-color: var(--linkLightBlue);
            border-radius: 20%;
            padding: 10px;
            margin: 5px 0;
        }

        #edit-item-form-area label input:not([type="checkbox"]),
        #edit-item-form-area label textarea {
            background-color: #FFFFFF;
            border: 1px solid #CCCCCC;
            border-radius: 3px;
        }

        .hide-input {
            margin: 10px 0;
        }

        .hide-input input[type="checkbox"] {
            margin: 0 5px;
        }

        #search-type {
            width: 100%;
            margin-bottom: 20px;
            display: flex;
            outline: 1px solid var(--linkDarkBlue);
        }

        #search-type > input[type="checkbox"] {
            display: none;
        }

        #search-type > span {
            width: 50%;
            text-align: center;
            padding: 5px 0;
        }

        #search-type > input:checked + span,
        #search-type > input:not(:checked) + span + span {
            color: var(--linkWhite);
            background-color: var(--linkDarkBlue);
        }

        #search-type > input:not(:checked) + span,
        #search-type > input:checked + span + span {
            background-color: #ddd;
        }

        #filter-search-form {
            display: block;
            margin-bottom: 20px;
        }

        #filter-search-form label {
            margin-bottom: 10px;
        }

        #filter-search-form label input {
            background-color: #FFFFFF;
            border: 1px solid #CCCCCC;
            border-radius: 3px;
        }

        #group-search-form {
            display: none;
        }

        #group-search-form > p {
            display: flex;
            align-items: center;
        }

        #group-search-form > p > label {
            padding: 0;
        }

        #group-search-form > p > img:last-child {
            margin-left: 3%;
        }

        #group-search-form > p > label + img {
            width: 45px;
            height: 45px;
        }

        #search-form input[type="button"] {
            margin-top: 0;
        }

        #group-edit-form-area{
            z-index: 2;
        }
        #group-edit-form-area .form-area p:first-child{
            border-bottom: solid 1px var(--linkLightBlue);
            padding-left: 10px;
            color: var(--linkDarkBlue);
        }
        #group-edit-form-area .form-area #group-delete-submit{
            background-color: #d33;
        }
        .group-name-input {
            margin-bottom: 20px;
        }
        .group-name-input > input[type="text"] {
            background-color: #FFFFFF!important;
            border: 1px solid #CCCCCC;
            border-radius: 3px;
        }
        #group-delete-submit {
            margin-top: 10px;
        }

        #active-image-form-area p {
            margin-bottom: 20px;
        }
        #active-image-form-area .image-input{
            margin-bottom: 20px;
        }
        #active-image-form-area .image-input > img {
            width: 50px;
            height: 50px;
            margin-right: 10px;
            border: solid 3px #aaa;
            border-radius: 30%;
        }
        #active-image-form-area .image-input > span {
            color: var(--linkGreen);
        }
        #active-image-form-area #upload-images-view{
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        #active-image-form-area #upload-images-view img{
            margin-bottom: 10px;
        }
        #img-upload-form input[type="button"] {
            margin: 0;
        }

        #batch-message-form textarea {
            background-color: #FFFFFF;
            border: 1px solid #CCCCCC;
            border-radius: 3px;
        }

        .check-list{
            width: 100%;
            margin-bottom: 10px;
        }
        .check-list label {
            display: flex;
            align-items: center;
            width: 100%;
            padding: 0 5%;
            margin: 0;
        }
        .check-list label img {
            align-self: center;
            width: 50px;
            margin: 5px;
            padding: 8px;
            border: solid 3px #aaa;
            border-radius: 30%;
        }
        .check-list label span{
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            flex-grow: 1;
            font-size: 13pt;
            color: var(--linkGreen);
        }
        .check-list label input[type="checkbox"] {
            margin-left: 3%;
        }

        .controll-area{
            width: 100%;
            margin: auto;
            display: flex;
            justify-content: space-between;
        }
        .controll-area > button{
            flex-grow: 1;
            margin: 1%;
            border: solid 5px var(--linkLightBlue);
            border-radius: 25%;
            text-align: center;
            overflow: hidden;
        }
        .controll-area > button > img{
            width: 80%;
            padding: 10%;
        }
        .controll-area > button > img:hover{
            opacity: 0.6;
        }

        @media screen and (min-width: 1000px) {
            .batch-edit-diarie {
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

    <div id="original_alert"></div>

    <p style="height: 10px;"></p>

    <!-- 既存のレコード表示欄 -->
    <section id="exist-item-area">
        <table id="exist-item-table">
            <thead>
                <tr><th>時間</th><th>支援内容</th><th>様子</th><th>非表示</th></tr>
            </thead>
            <!-- 既存のレコード -->
            <tbody class="viewer">
                @if($datas["group_items"])
                @foreach($datas["group_items"] as $event)
                <tr onclick="openEditItemForm(this, {{ $event["id"] }})">
                    <td>{{ $event["time"]->format('H:i') }}</td>
                    <td>{{ $event["activity"] }}</td>
                    <td>{{ $event["comment"] }}</td>
                    <td>{{ [" ", "非"][$event->parent_hidden] }}</td>
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
    </section>

    <!-- レコード入力項目 -->
    <datalist id="items">
        @foreach($datas["items"] as $item)
        <option value={{$item}}></option>
        @endforeach
    </datalist>

    <!-- 絞り込みフォーム -->
    <section id="search-form-area" class="ui-area">
        <form id="search-form" class="form-area">
            <label id="search-type">
                <input type="checkbox" onchange="setSearchTypeIsFilter(this.checked)" checked>
                <span>条件</span>
                <span>グループ</span>
            </label>
            <input type="hidden" name="facilitie_id" value="{{ $datas["facilitie"]["id"] }}">
            <section id="filter-search-form">
                <label>
                    <span>学校名</span>
                    <input type="text" name="school_name">
                </label>
                <label>
                    <span>利用日</span>
                    <input id="search-date" type="date" name="date" value="{{ date('Y-m-d') }}">
                </label>
                <label>
                    <span>年齢</span>
                    <input type="number" name="old" min=0>
                </label>
            </section>
            <section id="group-search-form" class="check-list">
                <p><label><img src="../img/logo/plus.png"><span>グループ作成</span><input type="button" onclick="openEditGroupForm(0)" style="display: none;"></label></p>
                @foreach($datas["facilitie"]->getGroups() as $group)
                <p>
                    <label>
                        <img src="{{ $group->icon_path }}">
                        <span>{{ $group->name }}</span>
                        <input name="group_ids[]" type="checkbox" value="{{ $group->id }}">
                    </label>
                    <img src="../img/logo/settings.png" onclick="openEditGroupForm({{ $group->id }})">
                </p>
                @endforeach
            </section>
            <input type="button" value="検索" onclick="sendSearch()">
        </form>
    </section>

    <!-- グループ編集フォーム -->
    <section id="group-edit-form-area" class="ui-area ui-area-invisible-background">
        <form id="group-edit-form" name="group_edit_form" class="form-area">
            <p><span style="cursor: pointer;" onclick="document.getElementById('group-edit-form-area').style.display = 'none';"> &lt;&ensp; </span><span id="group-edit-title">グループ作成</span></p>
            <input type="hidden" name="group_id" value="0">
            <input type="hidden" name="facilitie_id" value="{{ $datas["facilitie"]["id"] }}">
            <label class="group-name-input"><span>グループ名</span><input type="text" name="name"></label>
            <div class="check-list">
                @foreach($datas["facilitie"]->getClients() as $client)
                <label>
                    <img src="{{ $client->icon_path }}">
                    <span>{{ $client->name }}</span>
                    <input type="checkbox" name="client_ids[]" value="{{ $client->id }}">
                </label>
                @endforeach
            </div>
            <input id="group-edit-submit" type="button" value="作成" onclick="sendUpdateGroupInfo()">
            <input id="group-delete-submit" type="button" value="削除" onclick="sendDeleteGroupInfo()">
        </form>
    </section>

    <!-- 一括入力フォーム -->
    <form id="batch-data-form" name="batch_data_form">
        <input type="hidden" name="facilitie_id" value="{{ $datas["facilitie"]["id"] }}">
        <input type="hidden" name="share_item_id" value=-1>
        <input id="batch-data-date" type="hidden" name="date" value="{{ date('Y-m-d') }}">
        <!-- 児童リスト -->
        <section class="button-area">
            <input type="button" value="絞り込み" onclick="openSearchForm()">
            <input type="button" value="全員表示" onclick="sendSearchAll()">
            <input type="button" value="全選択" onclick="setAll()">
        </section>
        <div id="client-list" class="check-list"></div>
        <!-- レコード入力フォーム -->
        <section id="edit-item-form-area" class="ui-area">
            <div class="form-area">
                <p>一括入力項目</p>
                <label>
                    <span>時間</span>
                    <input type="time" name="time" value="{{ (new DateTime('now'))->format('H:i') }}">
                </label>
                <label>
                    <span>活動</span>
                    <input type="text" name="activity" list="items" autocomplete="off">
                </label>
                <label>
                    <span>詳細</span>
                    <textarea name="comment"></textarea>
                </label>
                <label class="hide-input">
                    <input type="checkbox" name="hidden">
                    <span>非表示にする</span>
                </label>
                <input id="item-send-button" type="button" value="一括登録" onclick="sendBatchActivity()">
                <input id="item-delete-button" type="button" value="削除" onclick="sendDeleteBatchActivity()">
            </div>
        </section>
    </form>

    <!-- 画像アップロードフォーム -->
    <section id="active-image-form-area" class="img-uploader ui-area">
        <section class="form-area">
            <p>活動写真</p>
            <!-- 画像追加・編集用フォーム -->
            <label class="image-input">
                <img src="../img/logo/plus.png"><span>画像選択</span>
                <input type="file" name="original_active_imgs[]" style="display: none;" accept="image/*" multiple>
            </label>
            <section id="upload-images-view"></section>
            <form enctype="multipart/form-data" id="img-upload-form">
                @csrf
                <input type="hidden" name="facilitie_id" value="{{ $datas["facilitie"]["id"] }}">
                <input type="hidden" name="date" value="{{ date('Y-m-d') }}">
                <input type="button" value="追加" onclick="sendActiveImage()">
            </form>
        </section>
    </section>

    <!-- 一括メッセージフォーム -->
    <section id="batch-chat-area" class="ui-area">
        <form id="batch-message-form" class="form-area">
            <p>一括メッセージ送信</p>
            <input type="hidden" name="facilitie_id" value="{{ isset($datas["facilitie"])? $datas["facilitie"]["id"] : null }}">
            <textarea name="body" placeholder="メッセージを入力してください"></textarea>
            <input type="button" value="送信" onclick="sendBatchMessage()">
        </form>
    </section>

    <!-- 操作ボタン -->
    <section class="controll-area">
        <button type="button" class="exe-btn" onclick="openActiveImageForm()"><img src="../img/logo/image.png"></button>
        <button type="button" class="exe-btn" onclick="openItemForm()"><img src="../img/logo/add_list.png"></button>
        <button type="button" class="exe-btn" onclick="openChatForm()"><img src="../img/logo/chat.png"></button>
    </section>

    <script src="{{ mix('js/original_alert.js') }}"></script>

    <script>
        // 初期化
        sendSearch();
        setSearchTypeIsFilter(true);

        // 受け取ったgroupsデータをjson化する
        const GROUPS = @json($datas["groups"]);
        // 画像フォームデータ
        var imgFormData = new FormData(document.getElementById("img-upload-form"));

        // 入力フォーム外をクリック時、入力フォームを非表示にする
        for(let area of document.getElementsByClassName("ui-area")){
            area.onclick = (e)=>closeForm(area, e.target);
        }

        // 絞り込みルールを切り替え
        function setSearchTypeIsFilter(isFilter){
            const searchType = ["group","filter"];
            let key = isFilter? 1 : 0;
            // 絞り込みフォームのリセット
            for(let name of ["school_name", "date", "old"]){
                let item = document.querySelector("#search-form [name="+name+"]");
                item.value = "";
                if(isFilter && name=="date") item.value = "{{ $datas["date"] }}";
            }
            for(let group_id of document.getElementsByName("group_ids[]")){
                group_id.checked = false;
            }
            // 絞り込み項目[条件/グループ]の切り替え
            document.getElementById(searchType[key]+"-search-form").style.display = "block";
            document.getElementById(searchType[(key+1)%2]+"-search-form").style.display = "none";
        }

        // フォームを非表示にする
        function closeForm(area, target){
            if(area == target){
                area.style.display = "none";
            }
        }

        // 絞り込みフォームを表示する
        function openSearchForm(){
            let form = document.getElementById("search-form-area");
            form.style.display = "flex";
        }

        // グループ編集フォームを表示する
        function openEditGroupForm(group_id=0){
            let form = document.getElementById("group-edit-form-area")
            // タイトル・送信ボタンを更新
            let text = (group_id==0)? "作成" : "更新";
            document.getElementById("group-edit-title").innerText = text;
            document.getElementById("group-edit-submit").value = text;
            let display = (group_id==0)? "none" : "inline";
            document.getElementById("group-delete-submit").style.display = display;
            // group_idを更新
            document.querySelector("#group-edit-form-area [name='group_id']").value = group_id;
            // グループに所属する児童のcheckboxを更新
            if(group_id == 0){  // 新規作成の場合、すべて空
                document.group_edit_form.name.value = "";
                for(let checkbox of document.querySelectorAll("#group-edit-form-area [name='client_ids[]']")){
                    checkbox.checked = false;
                }
            }else{  // 既存のグループの場合、情報を反映
                document.group_edit_form.name.value = GROUPS[group_id]["name"];
                for(let checkbox of document.querySelectorAll("#group-edit-form-area [name='client_ids[]']")){
                    checkbox.checked = GROUPS[group_id]["client_ids"].includes(parseInt(checkbox.value));
                }
            }

            form.style.display = "flex";
        }

        // 記録項目登録フォームを表示する
        function openItemForm(){
            document.getElementById("item-send-button").value = "一括登録";
            document.getElementById("item-delete-button").style.display = "none";
            document.getElementById("edit-item-form-area").style.display = "flex";
        }

        // 記録項目編集フォームを表示する
        function openEditItemForm(tr, item_id){
            let tds = tr.querySelectorAll("td");
            document.batch_data_form.share_item_id.value = item_id;
            document.batch_data_form.time.value = tds[0].innerText;
            document.batch_data_form.activity.value = tds[1].innerText;
            document.batch_data_form.comment.value = tds[2].innerText;
            document.batch_data_form.parent_hidden.checked = !(tds[3].innerText=="");
            document.getElementById("item-send-button").value = "一括編集";
            document.getElementById("item-delete-button").style.display = "block";
            document.getElementById("edit-item-form-area").style.display = "flex";
        }

        // 活動画像選択フォームを表示する
        function openActiveImageForm(){
            let form = document.getElementById("active-image-form-area");
            form.style.display = "flex";
        }

        // メッセージ送信フォームを表示する
        function openChatForm(){
            document.getElementById("batch-chat-area").style.display = "flex";
        }

        // 全選択ボタンクリック時に全選択。既に全選択なら全選択解除
        function setAll() {
            let cboxs = $("#client-list label>input[type='checkbox']");
            let allTrue = true;
            // 全選択
            for (let cbox of cboxs) {
                if (!cbox.checked) {
                    cbox.checked = true;
                    allTrue = false;
                }
            }
            // 一度もtrueにしなかった(全選択済み)場合、全選択解除
            if (allTrue) {
                for (let cbox of cboxs) {
                    cbox.checked = false;
                }
            }
        }

        // 絞り込み情報を送信
        function sendSearch() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "./batch_search",
                type: "POST",
                //dataType: "json",
                data: $("#search-form").serializeArray(),
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                timeout: 5000,
            }).done(function (data) {
                console.log(data);
                updateClientList(data);
            }).fail(function (e) {
                justAlert("絞り込みに失敗しました", "失敗");
                console.log(e);
            });
            document.getElementById("search-form-area").style.display = "none";
        }

        // 絞り込み情報なしで送信
        function sendSearchAll() {
            let datas = $("#search-form").serializeArray();
            datas.filter(e=>e.name!="facilitie_id").forEach(e=>e.value="");
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "./batch_search",
                type: "POST",
                //dataType: "json",
                data: datas,
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                timeout: 5000,
            }).done(function (data) {
                console.log(data);
                updateClientList(data);
            }).fail(function (e) {
                alert("絞り込み失敗...");
                justAlert("絞り込みに失敗しました", "失敗");
                console.log(e);
            });
            document.getElementById("search-form-area").style.display = "none";
        }

        // 絞り込み情報を受け、表示を更新する
        function updateClientList(data) {
            let html = '';
            for (let client of data["clients"]) {
                html += '<label>\
                            <img src="' + client["icon_path"] + '">\
                            <span>' + client["name"] + '</span>\
                            <input type="checkbox" name="client_ids[]" value="' + client["id"] + '">\
                         </label>';
            }
            if(html=='') html += "<p style='text-align: center;'>条件に合致する児童はいません</p>";
            $("#client-list").html(html);
        }

        // cliet_idのリストと、活動情報を送信する
        function sendBatchActivity() {
            document.getElementById("batch-data-date").value = document.getElementById("search-date").value;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "./regist_batch_activity",
                type: "POST",
                //dataType: "json",
                data: $("#batch-data-form").serializeArray(),
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                timeout: 5000,
            }).done(function (data) {
                justAlert("送信しました", "成功");
                console.log(data);
                document.getElementById("edit-item-form-area").style.display = "none";
            }).fail(function (e) {
                justAlert("送信できませんでした", "失敗");
                console.log(e);
            });
        }

        // 一括で登録したアイテムを削除する
        function sendDeleteBatchActivity(){
            document.getElementById("batch-data-date").value = document.getElementById("search-date").value;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "./delete_batch_activity",
                type: "POST",
                //dataType: "json",
                data: $("#batch-data-form").serializeArray(),
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                timeout: 5000,
            }).done(function (data) {
                justAlert("送信しました", "成功");
                console.log(data);
                document.getElementById("edit-item-form-area").style.display = "none";
            }).fail(function (e) {
                justAlert("送信できませんでした", "失敗");
                console.log(e);
            });
        }

        // グループ情報を更新する
        function sendUpdateGroupInfo(){
            document.getElementById("batch-data-date").value = document.getElementById("search-date").value;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "./update_group",
                type: "POST",
                //dataType: "json",
                data: $("#group-edit-form").serializeArray(),
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                timeout: 5000,
            }).done(function (data) {
                justAlert("更新しました", "成功");
                console.log(data);
                setTimeout(function() {
                    location.reload();
                }, 700);
            }).fail(function (e) {
                justAlert("更新できませんでした", "失敗");
                console.log(e);
            });
        }

        // グループを削除する
        function sendDeleteGroupInfo(){
            document.getElementById("batch-data-date").value = document.getElementById("search-date").value;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "./delete_group",
                type: "POST",
                //dataType: "json",
                data: $("#group-edit-form").serializeArray(),
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                timeout: 5000,
            }).done(function (data) {
                justAlert("削除しました", "成功");
                console.log(data);
                setTimeout(function() {
                    location.reload();
                }, 700);
            }).fail(function (e) {
                justAlert("削除できませんでした", "失敗");
                console.log(e);
            });
        }

        // アップロード画像選択時
        $('.img-uploader input[name="original_active_imgs[]"]').on('change', function(e){
            $('#upload-guide').css('display', 'none');
            let imageViewer = document.getElementById("upload-images-view");
            imageViewer.innerHTML = "";
            for(let file of e.target.files){
                var reader = new FileReader();
                var smallImages = [];
                reader.onload = function(e2){
                    // 選択された画像を表示
                    imageViewer.innerHTML += "<img src=" + e2.target.result + ">";
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

        // 画像をアップロードする
        function sendActiveImage(){
            document.querySelector("#img-upload-form input[name='date']").value = document.getElementById("search-date").value;
            /*
            for(let oldClientId of document.querySelectorAll("#img-upload-form  input[name='client_ids[]']")){
                oldClientId.remove();
            }
            */
            imgFormData.delete("client_ids[]");
            for(let clientId of document.querySelectorAll("#client-list input:checked")){
                //document.getElementById("img-upload-form").innerHTML += "<input type='hidden' name='client_ids[]' value="+clientId.value+">";
                imgFormData.append("client_ids[]", clientId.value);
            }
            $.ajax({
                async: false,
                type: "POST",
                url: "./upload_batch_image",
                data: imgFormData,
                dataType: "text",
                cache: false,
                contentType: false,
                processData: false,
                error: function (XMLHttpRequest) {
                    console.log(XMLHttpRequest);
                    justAlert("アップロードできませんでした", "失敗");
                },
                success: function (res) {
                    location.reload();
                }
            });
            document.getElementById("active-image-form-area").display = "none";
        }

        // 一括メッセージを送信する
        function sendBatchMessage(){
            let messageFormData = new FormData(document.getElementById("batch-message-form"));
            messageFormData.delete("client_ids[]");
            for(let clientId of document.querySelectorAll("#client-list input:checked")){
                messageFormData.append("client_ids[]", clientId.value);
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "./regist_batch_message",
                type: "POST",
                data: messageFormData,
                dataType: "text",
                timeout: 5000,
                cache: false,
                contentType: false,
                processData: false,
                async: false,
            }).done(function (data) {
                justAlert("送信しました", "成功");
                console.log(data);
                document.getElementById("batch-chat-area").style.display = "none";
                $('#batch-message-form textarea').val('');
            }).fail(function (e) {
                justAlert("送信できませんでした", "失敗");
                console.log(e);
            });
        }
    </script>
</section>
