@extends('layouts.parent', ["datas" => $datas])

@section('styles')
<style>
    #content{
        margin: calc(var(--headerHeight80pxVer) + 20px) 5% 100px;
    }
    #content a{
        color: inherit;
    }
    .list-toggle{
        font-size: 15pt;
        text-align: center;
        padding: 2%;
        margin-bottom: 0;
        border-top: solid 1px #ddd;
        border-bottom: solid 1px #ddd;
    }
    .list-toggle:hover{
        opacity: 0.6;
    }
    .message-list{
        padding: 10px;
        border-bottom: solid 1px #ddd;
    }
</style>
@endsection

@section('content')
<div id="content">
    @php
    $keys = [
        "facilitie" => "施設",
        "client" => "利用者",
        "worker" => "職員",
        "official" => "お問い合わせ"
    ];
    $list_datas = [];
    foreach($datas["message_list"] as $k => $message_list){
        $list_datas[$k] = [];
        foreach($message_list as $li){
            $list_datas[$k][] = [
                "url" => "./chat?child_id=".$datas["child"]["id"]."&facilitie_id=". $li["facilitie_id"] ."&client_id=". $li["client_id"] ."&other_id=". $li["other_id"],
                "img_path" => $li["icon_path"],
                "number" => $li["unread"],
                "title" => $li["name"],
                "note" => $li["updated"],
                "subtitle" => $li["last_msg"]
            ];
        }
    }
    @endphp
    @foreach($keys as $k => $key)
    @if(isset($list_datas[$k]))
    <p class="list-toggle">{{ $key }}</p>
    @component('components.list',["list_datas" => $list_datas[$k]])
    @endcomponent
    @endif
    @endforeach
    <script>
        $(".list-toggle").on("click",function(e) {
            $(e.target).next().slideToggle();
        });
    </script>
@endsection
