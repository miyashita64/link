@extends('layouts.teacher', ["datas" => $datas])

@section('styles')
<style>
#content{
    margin: var(--headerHeight80pxVer) 3% 100px;
}
#seat-table-form-area{
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 15pt;
    color: var(--linkGreen);
    padding-top: 10px;
}
#seat-table-form-area input{
    text-align: center;
    background-color: #eee;
    margin: 0 10px;
}
#seat-table-form-area #student-id-selector{
    display: none;
    margin-top: 3px;
    background-color: rgba(255,255,255,0.7);
    border: solid 1px var(--linkDarkBlue);
    z-index: 3;
}
#seat-table-form-area #student-id-selector label{
    display: block;
    margin: 0;
}
#seat-table-form-area #student-id-selector label input[type="radio"]{
    display: none;
}
#seat-table-form-area #student-id-selector label input + span{
    display: block;
}
#seat-table-form-area #student-id-selector label input[type="radio"]:checked + span{
    background-color: var(--linkLightBlue)
}

#rest-students{
    display: flex;
    font-size: 15pt;
    border-bottom: solid 3px var(--linkGreen);
}
#rest-students span{
    margin: 0 5px;
}

#send-change-seat-table-button{
    display: block;
    width: 100%;
    font-size: 15pt;
    text-align: center;
    color: var(--linkWhite);
    background-color: var(--linkDarkBlue);
    margin: 10px 0;
}
</style>
@endsection

@section('content')
<div id="content">
    <section id="seat-table-form-area">
        <form id="seat-table-form" method="POST" action="./classroom_seat_table_change">
            @csrf
            <input type="hidden" name="classroom_id" value="{{ $datas["classroom"]["id"] }}">
            <input type="hidden" name="row" value="">
            <input type="hidden" name="column" value="">
            <section id="student-id-selector">
                <label>
                    <input type="radio" name="student_id" value="" onchange="sendChangeSeatTable()">
                    <span>空席</span>
                </label>
                @foreach($datas["seats"]["none-seat"] as $student)
                <label>
                    <input type="radio" name="student_id" value="{{ $student->id }}" onchange="sendChangeSeatTable()">
                    <span>{{ $student->name }}</span>
                </label>
                @endforeach
            </section>
            <label><span>横：</span><input type="number" name="column_size" min="0" value="{{ $datas["classroom"]["column_size"] }}"></label>
            <label><span>縦：</span><input type="number" name="row_size" min="0" value="{{ $datas["classroom"]["row_size"] }}"></label>
        </form>
    </section>
    <input id="send-change-seat-table-button" type="button" value="座席表更新" onclick="sendChangeSeatTable()">

    <!-- 座席表 -->
    <section id="student-ids">
        @component('components.teacher.seat_table', ["datas" => $datas])
        @endcomponent
    </section>

    <section id="rest-students">
        @foreach($datas["seats"]["none-seat"] as $student)
        <span>{{ $student->name }}, </span>
        @endforeach
    </section>
</div>
@endsection

@section('scripts')
<script>
    window.onload = ()=>{
        // 席を選択時の処理を設定
        for(let row in document.querySelectorAll(".seat-table-row")){
            if(isNaN(row) || row==0){
                continue;
            }
            let key = row - 0 + 1;
            let seatElements = document.querySelectorAll(".seat-table-row:nth-of-type("+key+") .seat");
            for(let column in seatElements){
                seatElements[column].onclick = (e) => openRestStudentList(e, row-1, column);
            }
        }
        // 席の選択を最新の一つだけにする
        for(let student_id of document.querySelectorAll("#student-ids input[name='student_ids[]']")){
            student_id.onchange = (e)=>{
                for(let student_id of document.querySelectorAll("#student-ids input[name='student_ids[]']")){
                    if(student_id != e.target) student_id.checked = false;
                }
            };
        }
    }

    // 座席情報更新フォームを送信
    function sendChangeSeatTable(){
        let form = document.getElementById("seat-table-form");
        form.submit();
    }

    // 席がクリックされたときの処理
    function openRestStudentList(e, row, column){
        let row_elm = document.querySelector("#seat-table-form input[name='row']");
        let col_elm = document.querySelector("#seat-table-form input[name='column']");

        row_elm.value = row;
        col_elm.value = column;

        let id_selector = document.getElementById("student-id-selector");
        id_selector.style.display = "block";
        id_selector.style.position = "absolute";

        let rect = e.target.getBoundingClientRect();
        if(rect.top!=0 || rect.left!=0){
            id_selector.style.top = (window.pageYOffset + rect.bottom) + "px";
            id_selector.style.left = (window.pageXOffset + rect.left) + "px";
        }

        id_selector.focus();
    }
</script>
@endsection
