@extends('layouts.link_admin', ["datas" => $datas])

@section('styles')
<style>
    #content{
        margin: calc(var(--headerHeight80pxVer) + 20px) 0% 100px;
    }
    #content .slide-title{
        display: block;
        width: 90%;
        color: var(--linkGreen);
        margin: 0 auto;
        border-bottom: solid 1px #aaa;
        padding: 20px 10%;
    }
    #content .slide-title+.ad-list{
        width: 90%;
        margin: 0 auto;
        margin-bottom: 20px;
        display: none;
        overflow-x: scroll;
    }
    #content .slide-title+.ad-list > table{
        width: 80%;
        margin: 0 auto;
    }
    #content .slide-title+.ad-list > table > thead > tr > th{
        text-align: center;
        border-right: solid 1px #aaa;
        background-color: #eee;
    }
    #content .slide-title+.ad-list > table > thead > tr > th:last-child{
        border: none;
    }
    #content .slide-title+.ad-list > table > tbody > tr{
        background-color: #fff;
    }
    #content .slide-title+.ad-list > table > tbody > tr:nth-child(2n){
        background-color: #eee;
    }
    #content .slide-title+.ad-list > table > tbody > tr > td{
        padding: 0 10px;
    }

    #content .freeze-button,
    #content .unfreeze-button{
        width: 100%;
        color: #fff;
        padding: 5px;
        border: solid 1px #fff;
        text-align: center;
    }
    #content .freeze-button{
        background-color: #d00;
    }
    #content .unfreeze-button{
        background-color: #08d;
    }

    #content form{
        display: flex;
        flex-direction: column;
        font-size: 15pt;
    }
    #content form > *{
        width: 80%;
        margin: 0 auto;
        text-align: center;
    }
    #content form > label{
        display: flex;
        margin: 10px auto;
    }
    #content form > label > span{
        width: 300px;
    }
    #content form > label > input,
    #content form > label > select{
        width: 100%;
        flex-grow: 1;
    }
    #content form input{
        background-color: #ddd;
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
        overflow-y: scroll;
        padding: 3% 5% 8%;
        font-size: 13pt;
        background-color: #fff;
    }
    .ui-area > .form-area > form{
        display: flex;
        justify-content: space-around;
    }
    .ui-area > .form-area input{
        width: 30%;
        padding: 5%;
        text-align: center;
        color: #fff;
    }
    .ui-area > .form-area .freeze-submit[type="button"]{
        background-color: #f00;
    }
    .ui-area > .form-area input[type="button"]{
        background-color: #666;
    }
</style>
@endsection

@section('content')
<div id="content">
    <!-- データ一覧 -->
    <h2 class="slide-title">データ一覧</h2>
    <div class="ad-list">
        <h2 class="slide-title">Facilities 施設</h2>
        <div class="ad-list">
            <table>
                <thead>
                    <tr>
                        <th>id</th>
                        <th>name</th>
                        <th>light name</th>
                        <th>admin</th>
                        <th>created</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($datas["facilities"] as $facilitie)
                    <tr>
                        <td>{{ $facilitie["id"] }}</td>
                        <td>{{ $facilitie["name"] }}</td>
                        <td>{{ $facilitie["light_name"] }}</td>
                        <td>{{ $facilitie["admin_id"] }}: {{ $facilitie->getAdminName() }}</td>
                        <td>{{ $facilitie["created_at"] }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <h2 class="slide-title">Worker 職員</h2>
        <div class="ad-list">
            <table>
                <thead>
                    <tr>
                        <th>id</th>
                        <th>name</th>
                        <th>email</th>
                        <th>created</th>
                        <th>active</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($datas["users"] as $user)
                    @if($user["role"]==config('const.Roles.WORKER'))
                    <tr>
                        <td>{{ $user["id"] }}</td>
                        <td>{{ $user["name"] }}</td>
                        <td>{{ $user["email"] }}</td>
                        <td>{{ $user["created_at"] }}</td>
                        <td>
                            @if($user["active"]>0)
                            <button class="freeze-button" onclick="openFreezeWindow(this, {{ $user['id'] }})">凍結</button>
                            @else
                            <button class="unfreeze-button" onclick="openFreezeWindow(this, {{ $user['id'] }})">解凍</button>
                            @endif
                        </td>
                    </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>

        @if(Auth::user()["role"]==config('const.Roles.ROOT_ADMIN'))
        <h2 class="slide-title">Clients 児童</h2>
        <div class="ad-list">
            <table>
                <thead>
                    <tr>
                        <th>id</th>
                        <th>name</th>
                        <th>school</th>
                        <th>parent</th>
                        <th>created</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($datas["clients"] as $client)
                    <tr>
                        <td>{{ $client["id"] }}</td>
                        <td>{{ $client["name"] }}</td>
                        <td>{{ $client["school_name"] }}</td>
                        @php $parent = $client->getParent(); @endphp
                        @if(isset($parent))
                        <td>{{ $parent->id }}: {{ $parent->name }}</td>
                        @else
                        <td>未登録</td>
                        @endif
                        <td>{{ $client["created_at"] }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <h2 class="slide-title">Parent 保護者</h2>
        <div class="ad-list">
            <table>
                <thead>
                    <tr>
                        <th>id</th>
                        <th>name</th>
                        <th>email</th>
                        <th>created</th>
                        <th>active</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($datas["users"] as $user)
                    @if($user["role"]==config('const.Roles.PARENT'))
                    <tr>
                        <td>{{ $user["id"] }}</td>
                        <td>{{ $user["name"] }}</td>
                        <td>{{ $user["email"] }}</td>
                        <td>{{ $user["created_at"] }}</td>
                        <td>
                            @if($user["active"]>0)
                            <button class="freeze-button" onclick="openFreezeWindow(this, {{ $user['id'] }});">凍結</button>
                            @else
                            <button class="unfreeze-button" onclick="openFreezeWindow(this, {{ $user['id'] }});">解凍</button>
                            @endif
                        </td>
                    </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>

        <h2 class="slide-title">LinkAdmin リンク運営</h2>
        <div class="ad-list">
            <table>
                <thead>
                    <tr>
                        <th>id</th>
                        <th>name</th>
                        <th>email</th>
                        <th>created</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($datas["users"] as $user)
                    @if($user["role"]==config('const.Roles.ADMIN'))
                    <tr>
                        <td>{{ $user["id"] }}</td>
                        <td>{{ $user["name"] }}</td>
                        <td>{{ $user["email"] }}</td>
                        <td>{{ $user["created_at"] }}</td>
                    </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    <!-- お問い合わせ -->
    <h2 class="slide-title"><a href="chat_list">お問い合わせ</a></h2>
    <div></div>

    <!-- 新規施設登録 -->
    <h2 class="slide-title">新規施設登録</h2>
    <div class="ad-list">
        <form id="facilitie-register" method="POST" action="./regist_facilitie">
            @csrf
            <label><span>施設名</span><input type="text" name="name"></label>
            <label><span>施設略称</span><input type="text" name="light_name"></label>
            <label><span>事業所番号</span><input type="text" name="office_number"></label>
            <label><span>管理者ID</span><input type="number" name="admin_id"></label>
            <input type="submit" value="登録">
        </form>
    </div>

    <!-- 新規施設登録 -->
    <h2 class="slide-title">新規学校登録</h2>
    <div class="ad-list">
        <form id="school-register" method="POST" action="./regist_school" onsubmit="return diffSchoolPassword()">
            @csrf
            <label><span>学校名</span><input type="text" name="name" required></label>
            <label><span>email</span><input type="email" name="email" required></label>
            <label><span>パスワード</span><input type="password" name="password" required></label>
            <label><span>パスワード確認</span><input type="password" name="password_confirmation" required></label>
            <label><span>電話番号</span><input type="text" name="tel" required></label>
            <label><span>教員名</span><input type="text" name="teacher_name" required></label>
            <input type="submit" value="登録">
        </form>
    </div>

    @if(Auth::user()["role"]==config('const.Roles.ROOT_ADMIN'))
    <!-- ユーザ役職変更 -->
    <h2 class="slide-title">役職割り直し</h2>
    <div class="ad-list">
        <form id="update-user-permit" method="POST" action="./update_user_permit">
            @csrf
            <label><span>ユーザID</span><input type="number" name="user_id" min=0></label>
            <label>
                <span>役職</span>
                <select name="permit">
                    <option value="5">5:リンク運営</option>
                    <option value="10">10:職員</option>
                    <option value="15">15:保護者</option>
                    <option value="0">0:システム管理者</option>
                </select>
            </label>
            <input type="submit" value="更新">
        </form>
    </div>

    <!-- パスワード変更 -->
    <h2 class="slide-title">パスワード変更</h2>
    <div class="ad-list">
        <form id="update-user-password" method="POST" action="./update_user_password">
            @csrf
            <label><span>ユーザID</span><input type="number" name="user_id" min=0></label>
            <label><span>パスワード</span><input type="password" name="new_password"></label>
            <input type="submit" value="更新">
        </form>
    </div>

    <!-- アカウント凍結ウィンドウ -->
    <div id="freeze-window" class="ui-area">
        <section class="form-area">
            <p id="notice-text"></p>
            <form>
                <input type="hidden" id="freezer_id" name="freezer_id">
                <input type="hidden" id="freezer_value" name="freezer_value">
                <input type="button" class="freeze-submit" value="確定" onclick="sendFreezeInfo()">
                <input type="button" onclick="closeFreezeWindow()" value="キャンセル">
            </form>
        </section>
    </div>
    @endif
</div>

<script>
    let opd_button;
    $("#content .slide-title").on("click",(e)=>{
        $(e.target).next().slideToggle(700);
    });

    // 学校登録時のパスワードが一致しているかを判定する
    function diffSchoolPassword(){
        let pass = document.querySelector("#school-register [name='password']").value;
        let passc = document.querySelector("#school-register [name='password-confirm']").value;
        if(pass!=passc){
            alert("パスワードが確認用のものと一致しません！");
            return false;
        }
    }

    // 凍結情報送信
    function sendFreezeInfo(){
        // ajax送信
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: './freeze_user',
            type: 'POST',
            dateType: 'json',
            data: $("#freeze-window > .form-area > form").serializeArray(),
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            timeout: 5000,
        }).done(function(data){
            alert(data["message"]);
            // buttonの更新
            opd_button.classList.toggle("freeze-button");
            opd_button.classList.toggle("unfreeze-button");
            opd_button.innerText = "編集済み";
        }).fail(function(e){
            console.log(e);
            alert(e);
        });
        // windowを閉じる
        closeFreezeWindow();
    }

    // freezeWindowの表示
    function openFreezeWindow(button, id){
        let fz_val = button.classList.contains("freeze-button")? 0 : 1;
        $("#freeze-window #freezer_id").val(id);
        $("#freeze-window #freezer_value").val(fz_val);
        $("#freeze-window #notice-text").text(fz_val? "凍結を解除しますか?" : "凍結しますか？");
        $("#freeze-window").css("display", "flex");
        opd_button = button;
    }

    // freezeWindowの非表示
    function closeFreezeWindow(){
        let window = $("#freeze-window");
        window.css("display", "none");
    }
</script>
@endsection
