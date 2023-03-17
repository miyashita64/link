@extends('layouts.teacher', ["datas" => $datas])

@section('styles')
<style>
    #content{
        margin: calc(var(--headerHeight80pxVer) + 20px) 3% 100px;
    }
    #content > p:first-child{
        position: relative;
        color: var(--linkGreen);
        font-size: 20pt;
        padding: 10px;
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
    .ui-area > .form-area > form input,
    .ui-area > .form-area > form select,
    .ui-area > .form-area > form textarea{
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
    .ui-area > .form-area > form input[type="button"]:nth-of-type(2n){
        background-color: #d33;
    }

    #classmate-blocks{
        display: flex;
        align-items: flex-start;
        justify-content: space-around;
        flex-wrap: wrap;
        padding: 10px 0;
    }
    #classmate-blocks.add-classmate{
        background-color: #f5f5f5;
    }
    #classmate-blocks > .block{
        margin: 0;
        padding: 0;
    }
    #classmate-blocks > .block > p{
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        position: relative;
        max-width: 80px;
        color: var(--linkGreen);
        margin: 0 5px;
    }
    #classmate-blocks input[type="checkbox"],
    #classmate-blocks:not(.add-classmate) input[type="checkbox"]:not(:checked):not(.is-classmate) + p{
        display: none;
    }
    #classmate-blocks > .block input[type="checkbox"]:not(:checked) + p{
        opacity: 0.5;
    }
    #classmate-blocks > .block input[type="checkbox"]:not(.is-classmate):checked + p::after,
    #classmate-blocks > .block input[type="checkbox"].is-classmate:not(:checked) + p::after{
        position: absolute;
        top: 50px;
        z-index: 2;
        text-align: center;
        font-weight: bold;
    }
    #classmate-blocks > .block input[type="checkbox"]:not(.is-classmate):checked + p::after{
        content: "加入";
        color: greenyellow;
    }
    #classmate-blocks > .block .is-classmate[type="checkbox"]:not(:checked) + p::after{
        content: "脱退";
        color: red;
    }
    #classmate-blocks > .block img{
        width: 80px;
        border: solid 1px var(--linkLightBlue);
        border-radius: 10%;
    }
</style>
@endsection

@section('content')
<div id="content">
    @php
        $list_datas = [
            [
                "onclick" => "openClassroomInfoForm(0)",
                "title" => "新規クラス登録",
                "img_path" => "../img/logo/plus.png",
            ]
        ];
        foreach($datas["classrooms"] as $classroom){
            $ary = [$classroom["id"], '"'.$classroom["name"].'"'];
            $info = implode(",", $ary);
            $list_datas[] = [
                "onclick" => "openClassroomInfoForm(".$info.")",
                "title" => $classroom["name"],
                "img_path" => "../img/logo/account.png",
            ];
        }
    @endphp
    @component('components.list', ["list_datas" => $list_datas])
    @endcomponent

    <section id="classroom-info-form-area" class="ui-area">
        <section class="form-area">
            <p>クラス情報</p>
            <form id="classroom-info-form">
                @csrf
                <input type="hidden" name="classroom_id" value="0">
                <p><span>クラス名</span><input type="text" name="name" required></p>
                <p>
                    <span>担任</span>
                    <select name="teacher_id">
                        @foreach($datas["teachers"] as $teacher)
                        <option value={{ $teacher["id"] }}>{{ $teacher["name"] }}</option>
                        @endforeach
                    </select>
                </p>
                <p>クラス人数: <span id="classmate-length"></span></p>
                <section id="classmate-blocks">
                    <label class="block">
                        <input type="button" style="display: none;" onclick="document.getElementById('classmate-blocks').classList.toggle('add-classmate');">
                        <p><img src="../img/logo/plus.png">追加</p>
                    </label>
                    @foreach($datas["students"] as $student)
                    <label class="block">
                        <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" onchange="countClassmate()">
                        <p><img src="../img/logo/account.png">{{ $student->name }}</p>
                    </label>
                    @endforeach
                </section>
                <input type="button" value="更新" onclick="sendClassroomInfo(true)">
                <input type="button" value="削除" onclick="sendClassroomInfo(false)">
            </form>
        </section>
    </section>
</div>
@endsection

@section('scripts')
<script>
    const CLASSROOMS = @json($datas["classrooms"]);

    // 入力フォーム外をクリック時、入力フォームを非表示にする
    for(let area of document.getElementsByClassName("ui-area")){
        area.onclick = (e)=>closeForm(area, e.target);
    }

    // 選択中のクラスメート数を表示
    function countClassmate(){
        let count = document.querySelectorAll("input[name='student_ids[]']:checked").length;
        document.getElementById("classmate-length").innerText = count;
    }

    // フォームを非表示にする
    function closeForm(area, target){
        if(area == target){
            area.style.display = "none";
        }
    }

    // クラス情報フォームを開く
    function openClassroomInfoForm(classroom_id=0, name=""){
        document.querySelector("#classroom-info-form input[name='classroom_id']").value = classroom_id;
        document.querySelector("#classroom-info-form input[name='name']").value = name;
        let classroom = CLASSROOMS.find((classroom)=>classroom["id"]==classroom_id);
        document.getElementById("classmate-blocks").classList = (classroom)? "" : "add-classmate";
        document.querySelector("#classroom-info-form .block:first-child").style.display = (classroom)? "flex" : "none";
        for(let student_id of document.querySelectorAll("#classroom-info-form input[name='student_ids[]']")){
            if(classroom){  // 既存クラス
                let isClassmate = classroom["classmates"].find((classmate)=>classmate["id"]==student_id.value);
                student_id.checked = isClassmate;
                student_id.classList = (isClassmate)? "is-classmate" : "";
            }else{  // 新規クラス
                student_id.checked = false;
                student_id.classList = "";
            }
        }
        countClassmate();
        document.getElementById("classroom-info-form-area").style.display = "flex";
    }

    // クラス情報を更新
    function sendClassroomInfo(is_update){
        let classroomFormData = new FormData(document.getElementById("classroom-info-form"));
        let url = (is_update)? "./regist_classroom_list" : "./delete_classroom_list";
        $.ajax({
            async: false,
            type: "POST",
            url: url,
            data: classroomFormData,
            dataType: "text",
            cache: false,
            contentType: false,
            processData: false,
            error: function (XMLHttpRequest) {
                console.log(XMLHttpRequest);
                alert("アップロードに失敗しました");
            },
            success: function (res) {
                console.log(res);
                location.reload();
            }
        });
    }
</script>
@endsection
