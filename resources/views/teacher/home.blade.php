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
#classroom-edit{
    position: absolute;
    top: 10px;
    right: 10px;
    max-width: 110px;
    text-align: center;
    font-size: 13pt;
    color: var(--linkGreen);
    background-color: #def;
    border: solid 1px var(--linkLightBlue);
    z-index: 1;
}

#classroom-edit:hover{
    opacity: 0.7;
}

#classroom-edit > img{
    display: block;
    width: 60%;
    margin: 0 auto;
}
</style>
@endsection

@section('content')
<div id="content">
    <p>
        授業
        <a href="./classroom_list" id="classroom-edit">
            クラス管理
            <img src="../img/logo/account.png">
        </a>
    </p>
    @php
        $list_datas = [];
        foreach($datas["classrooms"] as $classroom){
            $list_datas[] = [
                "url" => "./classroom?classroom_id=".$classroom["id"],
                "title" => $classroom["name"],
                "img_path" => "../img/logo/people.png"
            ];
        }
    @endphp
    @component('components.list', ["list_datas" => $list_datas])
    @endcomponent
</div>
@endsection
