@extends('layouts.worker', ["datas" => $datas])
@section('styles')

<style>
    #content {
        margin: calc(var(--headerHeight80pxVer) + 20px) 3% 100px;
    }

    .none-facilitie {
        font-size: 15pt;
        margin: 1%;
        margin-top: 60px;
        padding: 10px;
        border: solid 5px var(--linkLightBlue);
        border-radius: 10%;
        text-align: center;
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
    @if(!isset($datas["facilitie"]))
    <p class="none-facilitie">左上の「勤務先追加」でQRコードを表示し、施設管理者がカメラで読み込むことで、施設職員として認証されます。</p>
    @else
    @php
        $list_datas = [
            [
                "url" => "./batch_edit_diarie?facilitie_id=". $datas["facilitie"]["id"],
                "img_path" => "../img/logo/edit.png",
                "title" => "一括操作"
            ]
        ];

        foreach($datas["clients"] as $client){
            $list_datas[] = [
                "url" => "./edit_diarie?client_id=". $client["id"] ."&facilitie_id=". $datas["facilitie"]["id"],
                "img_path" => $client["icon_path"],
                "title" => $client["name"]
            ];
        }
        $list_datas[] = [
            "url" => "./add_client?facilitie_id=".$datas["facilitie"]["id"],
            "img_path" => "../img/logo/plus.png",
            "title" => "新規利用者登録"
        ];
    @endphp
    @component('components.list', ["list_datas" => $list_datas])
    @endcomponent
    @endif
</div>
@endsection
