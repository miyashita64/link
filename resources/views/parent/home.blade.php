@extends('layouts.parent', ["datas" => $datas])

@section('styles')

<style>
    #content {
        margin: calc(var(--headerHeight80pxVer) + 20px) 5% 100px;
    }

    .none-client {
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
    @if(!isset($datas["clients"]))
    <p class="none-client">
        左上のボタンから利用者を登録してください。<br>
        その後、設定から「QRコード」を表示し、施設管理者がカメラで読み込むことで、「リンク」をご使用いただけます。
    </p>
    @else
    @component('components.diarie_view', ["datas" => $datas])
    @endcomponent
    @endif
</div>
@endsection
