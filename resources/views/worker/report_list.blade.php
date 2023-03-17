@extends('layouts.worker', ["datas" => $datas])

@section('styles')
<style>
    #content{
        margin: calc(var(--headerHeight80pxVer) + 20px) 3% 100px;
    }

    @media screen and (min-width: 1000px) {
        #content {
            width: 70%;
            margin-left: 15%;
        }
    }
</style>
@endsection

@section('content')
<div id="content">
    @php
        $list_datas = [
            [
                "url" => "./service_management?facilitie_id=".$datas["facilitie"]["id"],
                "title" => "サービス管理",
                "img_path" => "../img/logo/calendar.png"
            ],[
                "url" => "./service_report?facilitie_id=".$datas["facilitie"]["id"],
                "title" => "サービス提供記録",
                "img_path" => "../img/logo/documents.png"
            ],[
                "url" => "./transfer_report?facilitie_id=".$datas["facilitie"]["id"],
                "title" => "提供実績記録票",
                "img_path" => "../img/logo/document.png"
            ],[
                "url" => "./transfer?facilitie_id=".$datas["facilitie"]["id"],
                "title" => "送迎記録",
                "img_path" => "../img/logo/transfer.png"
            ],[
                "url" => "./worker_list?facilitie_id=".$datas["facilitie"]["id"],
                "title" => "職員一覧",
                "img_path" => "../img/logo/people.png"
            ]
        ];
    @endphp
    @component('components.list', ["list_datas" => $list_datas])
    @endcomponent
</div>
@endsection
