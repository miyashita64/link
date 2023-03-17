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
        $list_datas = [];
        if(isset($datas["grade"])){
            $list_datas = [
                [
                    "url" => "./student_list",
                    "title" => "学年一覧に戻る",
                    "img_path" => "../img/logo/list.png",
                ]
            ];
            foreach($datas["students"] as $student){
                $ary = [$student->id, '"'.$student->name.'"', $student->grade];
                if($student->entered_at){
                    $ary[] = '"'.explode(" ", $student->entered_at)[0].'"';
                    if($student->graduated_at) $ary[] = '"'.explode(" ", $student->graduated_at)[0].'"';
                }
                $info = implode(",", $ary);
                $list_datas[] = [
                    "onclick" => "openStudentInfoForm(".$info.")",
                    "title" => $student->name,
                    "img_path" => "../img/logo/account.png",
                ];
            }
        }else{
            $list_datas = [
                [
                    "onclick" => "openStudentInfoForm(0)",
                    "title" => "新規学生登録",
                    "img_path" => "../img/logo/plus.png",
                ]
            ];
            for($grade=1; $grade<=6; $grade++){
                $list_datas[] = [
                    "url" => "./student_list?grade=".$grade,
                    "title" => $grade."年生",
                    "img_path" => "../img/logo/children.png",
                ];
            }
            $list_datas[] = [
                "onclick" => "if(confirm('全学生を進級させます。')) window.location.href = './promote';",
                "title" => "進級",
                "img_path" => "../img/logo/plus.png",
            ];
        }
    @endphp
    @component('components.list', ["list_datas" => $list_datas])
    @endcomponent

    <section id="student-info-form-area" class="ui-area">
        <section class="form-area">
            <p>学生情報</p>
            <form id="student-info-form">
                @csrf
                <input type="hidden" name="student_id" value="0">
                <p><span>氏名</span><input type="text" name="name" required></p>
                <p><span>学年</span><input type="number" name="grade" min="1" max="6" value="{{ $datas["grade"] }}" required></p>
                <p><span>入学日</span><input type="date" name="entered_at"></p>
                <p><span>卒業日</span><input type="date" name="graduated_at"></p>
                <input id="send-student-info-button" type="button" value="送信" onclick="sendStudentInfo()">
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
    function openStudentInfoForm(student_id, name="", grade="1", entered_at="", graduated_at=""){
        document.querySelector("#student-info-form input[name='student_id']").value = student_id;
        document.querySelector("#student-info-form input[name='name']").value = name;
        document.querySelector("#student-info-form input[name='grade']").value = grade;
        document.querySelector("#student-info-form input[name='entered_at']").value = entered_at;
        document.querySelector("#student-info-form input[name='graduated_at']").value = graduated_at;
        document.getElementById("student-info-form-area").style.display = "flex";
    }

    // 学生情報を更新
    function sendStudentInfo(){
        let studentFormData = new FormData(document.getElementById("student-info-form"));
        $.ajax({
            async: false,
            type: "POST",
            url: "./update_student",
            data: studentFormData,
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
                // document.getElementById("student-info-form-area").style.display = "none";
            }
        });
    }
</script>
@endsection
