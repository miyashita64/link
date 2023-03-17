@extends('layouts.teacher', ["datas" => $datas])

@section('styles')
<style>
    #content{
        margin: calc(var(--headerHeight80pxVer) + 20px) 3% 100px;
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
    .ui-area > .form-area > form input, .ui-area > .form-area > form textarea{
        width: 100%;
        padding: 10px;
        background-color: #eee;
    }
    .ui-area > .form-area > form textarea:focus{
        height: 200px;
    }
    .ui-area > .form-area > form input[type="button"]{
        color: #fff;
        background-color: var(--linkDarkBlue);
        text-align: center;
        margin-top: 30px;
    }
</style>
@endsection

@section('content')
<div id="content">
    @php
        $list_datas = [
            [
                "onclick" => "openTeacherInfoForm(0)",
                "title" => "新規教員登録",
                "img_path" => "../img/logo/plus.png",
            ]
        ];
        foreach($datas["teachers"] as $teacher){
            $info = implode(",", [$teacher->id, '"'.$teacher->name.'"']);
            $list_datas[] = [
                "onclick" => "openTeacherInfoForm(".$info.")",
                "title" => $teacher->name,
                "img_path" => "../img/logo/account.png",
            ];
        }
    @endphp
    @component('components.list', ["list_datas" => $list_datas])
    @endcomponent

    <section id="teacher-info-form-area" class="ui-area">
        <section class="form-area">
            <p>教員情報</p>
            <form id="teacher-info-form">
                @csrf
                <input type="hidden" name="teacher_id" value="0">
                <p><span>氏名</span><input type="text" name="name" required></p>
                <input id="send-teacher-info-button" type="button" value="送信" onclick="sendteacherInfo()">
            </form>
        </section>
    </section>
</div>
@endsection

@section('scripts')
<script>
    // 入力フォーム外をクリック時、入力フォームを非表示にする
    for(let area of document.getElementsByClassName("ui-area")){
        area.onclick = (e)=>closeForm(area, e.target);
    }

    // フォームを非表示にする
    function closeForm(area, target){
        if(area == target){
            area.style.display = "none";
        }
    }

    // 学生情報フォームを開く
    function openTeacherInfoForm(teacher_id, name=""){
        document.querySelector("#teacher-info-form input[name='teacher_id']").value = teacher_id;
        document.querySelector("#teacher-info-form input[name='name']").value = name;
        document.getElementById("teacher-info-form-area").style.display = "flex";
    }

    // 学生情報を更新
    function sendteacherInfo(){
        let teacherFormData = new FormData(document.getElementById("teacher-info-form"));
        $.ajax({
            async: false,
            type: "POST",
            url: "./update_teacher",
            data: teacherFormData,
            dataType: "text",
            cache: false,
            contentType: false,
            processData: false,
            error: function (XMLHttpRequest) {
                console.log(XMLHttpRequest);
                alert("アップロードに失敗しました");
            },
            success: function (res) {
                location.reload();
            }
        });
    }
</script>
@endsection
