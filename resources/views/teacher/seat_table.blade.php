@extends('layouts.teacher', ["datas" => $datas])

@section('styles')
<style>
#content{
    margin: var(--headerHeight80pxVer) 0 100px;
}

input{
    width: 90%;
    padding: 10px;
}
input[type="text"],
textarea,
select{
    background-color: #eee;
}

.select-area{
    display: flex;
    align-items: center;
    justify-content: space-around;
    padding-top: 20px;
    margin: 0 10%;
    font-size: 15pt;
}
.select-area span{
    display: inline-block;
    color: var(--linkGreen);
    text-wrap: none;
    padding: 0 20px;
}
.select-area > section{
    display: flex;
    align-items: center;
    justify-content: space-around;

    width: 50%;
}
.select-area > section *:not(span){
    flex-grow: 1;
    width: inherit;
    height: 40px;
    text-align: center;
}

.toggle-switch > label{
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 20px 10% 0;
    font-size: 15pt;
    border: solid 1px var(--linkDarkBlue);
}
.toggle-switch > label input[type="checkbox"]{
    display: none;
}
.toggle-switch > label span{
    flex-grow: 1;
    text-align: center;
}
.toggle-switch > label input[type="checkbox"]:checked + span,
.toggle-switch > label input[type="checkbox"]:not(:checked) + span + span{
    color: var(--linkWhite);
    background-color: var(--linkDarkBlue);
}
.toggle-switch > label input[type="checkbox"]:checked + span + span,
.toggle-switch > label input[type="checkbox"]:not(:checked) + span{
    color: var(--linkDarkBlue);
    background-color: inherit;
}

#seat-change-button{
    position: absolute;
    right: 10%;
    background-color: var(--linkWhite);
    border: solid 2px var(--linkDarkBlue);
    border-radius: 10%;
    margin: 0 auto;
}
#seat-change-button a{
    display: inline-block;
    font-size: 12pt;
    text-align: center;
    color: var(--linkGreen);
    font-weight: bold;
    padding: 10px;
}

.controll-area{
    width: 90%;
    margin: auto 5%;
    display: flex;
    justify-content: space-between;
}
.controll-area > *{
    width: 50%;
}
.controll-area > button{
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
.controll-area .shortcut-form-area{
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: space-around;
}
.controll-area .shortcut-form-area .shortcut-form{
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: space-around;
}
.controll-area .shortcut-form-area .shortcut-form input[type="button"]{
    width: 30%;
    color: #fff;
    background-color: var(--linkDarkBlue);
    text-align: center;
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
.ui-area > .form-area{
    width: 70%;
    height: 70%;
    overflow-y: scroll;
    padding: 3% 5% 8%;
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
    margin-top: 30px;
}
#item-delete-button{
    background-color: #d33;
}
</style>
@endsection

@section('content')
<div id="content">
    <datalist id="subject-items">
        <option></option>
        <option value="国語">国語</option>
        <option value="算数">算数</option>
        <option value="英語">英語</option>
        <option value="生活">生活</option>
    </datalist>

    <section class="select-area">
        <input type="hidden" id="classroom_id" name="classroom_id" value="{{ $datas["classroom"]["id"] }}">
        <section>
            <span>科目</span>
            <input type="text" id="subject" name="subject" list="subject-items" autocomplete="off">
        </section>
        <section>
            <span>担当</span>
            <select id="teacher_id" value="{{ $datas["classroom"]["teacher_id"] }}">
                <option></option>
                @foreach($datas["teachers"] as $teacher)
                <option value="{{ $teacher["id"] }}">{{ $teacher["name"] }}</option>
                @endforeach
            </select>
        </section>
    </section>

    <!-- 表示切り替え -->
    <section class="toggle-switch">
        <label>
            <input type="checkbox" checked onchange="toggleStudentList(this.checked)">
            <span>座席表</span>
            <span>リスト</span>
        </label>
    </section>

    <!-- 学生選択 -->
    <section id="student-ids">
        <!-- 席替え -->
        <button id="seat-change-button" type="button">
            <a href="./classroom_seat_table_change?classroom_id={{ $datas["classroom"]["id"] }}">席替え</a>
        </button>
        @component('components.teacher.seat_table', ["datas" => $datas])
        @endcomponent
    </section>

    <!-- レコード入力項目 -->
    <datalist id="items">
        @foreach($datas["items"] as $item)
        <option value={{$item}}></option>
        @endforeach
    </datalist>

    <!-- UI -->
    <section class="controll-area">
        <button><img src="../img/logo/add_list.png" onclick="openItemEditForm()"></button>
        <section class="shortcut-form-area">
            <form class="shortcut-form">
                <input type="text" name="activity" list="items" autocomplete="off">
                <input type="button" value="送信" onclick="sendStudentActivityShort(this)">
            </form>
            <form class="shortcut-form">
                <input type="text" name="activity" list="items" autocomplete="off">
                <input type="button" value="送信" onclick="sendStudentActivityShort(this)">
            </form>
            <form class="shortcut-form">
                <input type="text" name="activity" list="items" autocomplete="off">
                <input type="button" value="送信" onclick="sendStudentActivityShort(this)">
            </form>
        </section>
    </section>

    <!-- レコード入力フォーム -->
    <section id="edit-item-form-area" class="ui-area">
        <form id="edit-item-form" class="form-area">
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
            <input id="item-send-button" type="button" value="登録" onclick="sendStudentActivity()">
        </form>
    </section>
</div>
@endsection

@section('scripts')
<script>
    // 入力フォーム外をクリック時、入力フォームを非表示にする
    for(let area of document.getElementsByClassName("ui-area")){
        area.onclick = (e)=>{
            if(area == e.target){
                area.style.display = "none";
            }
        }
    }

    // 学生がいない席の指定を無効化
    document.querySelectorAll("input[name='student_ids[]'][value='']").forEach(
        (blank_seat) => blank_seat.onchange = (e)=>{
            e.target.checked = false;
        }
    );

    // 表示方法の切り替え
    function toggleStudentList(isTable){
        let type = isTable? "table" : "list";
        for(let row of document.querySelectorAll(".seat-table-area > p")){
            row.classList = "seat-"+type+"-row";
        }
    }

    // 編集フォームを表示
    function openItemEditForm(){
        let form = document.getElementById("edit-item-form-area");
        let date = new Date();
        let twoDigit = (int) => int<10? "0"+int : ""+int;
        let time = twoDigit(date.getHours()) + ":" + twoDigit(date.getMinutes());
        form.querySelector('[name="time"]').value = time;
        form.querySelector('[name="activity"]').value = "";
        form.querySelector('[name="comment"]').value = "";
        form.style.display = "flex";
    }

    // student_idのリストと、活動情報を送信する
    function sendStudentActivity() {
        var editItemFormData = new FormData(document.getElementById("edit-item-form"));
        editItemFormData.delete("student_ids[]");
        for(let studentId of document.querySelectorAll("#student-ids input[name='student_ids[]']:checked")){
            editItemFormData.append("student_ids[]", studentId.value);
        }
        editItemFormData.append("subject", document.getElementById("subject").value);
        editItemFormData.append("teacher_id", document.getElementById("teacher_id").value);
        editItemFormData.append("classroom_id", document.getElementById("classroom_id").value);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "./regist_student_info",
            type: "POST",
            data: editItemFormData,
            dataType: "text",
            async: false,
            cache: false,
            contentType: false,
            processData: false,
        }).done(function (data) {
            alert("送信成功！")
            document.getElementById("edit-item-form-area").style.display = "none";
        }).fail(function (e) {
            alert("送信失敗...");
            console.log(e);
        });
    }

    // student_idのリストと、活動情報を送信する（ショートカット版）
    function sendStudentActivityShort(target) {
        var editItemFormData = new FormData(target.parentNode);
        editItemFormData.delete("student_ids[]");
        for(let studentId of document.querySelectorAll("#student-ids input[name='student_ids[]']:checked")){
            editItemFormData.append("student_ids[]", studentId.value);
        }
        editItemFormData.append("subject", document.getElementById("subject").value);
        editItemFormData.append("teacher_id", document.getElementById("teacher_id").value);
        editItemFormData.append("classroom_id", document.getElementById("classroom_id").value);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "./regist_student_info",
            type: "POST",
            data: editItemFormData,
            dataType: "text",
            async: false,
            cache: false,
            contentType: false,
            processData: false,
        }).done(function (data) {
            alert("送信成功！")
            document.getElementById("edit-item-form-area").style.display = "none";
        }).fail(function (e) {
            alert("送信失敗...");
            console.log(e);
        });
    }
</script>
@endsection
