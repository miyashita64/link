@extends('layouts.worker', ["datas" => $datas])

@section('styles')
<style>
    #content{
        margin: calc(var(--headerHeight80pxVer) + 20px) 3% 100px;
    }
    #service-data .date{
        width: 100%;
        display: inline-block;
        text-align: center;
        font-size: 20pt;
        font-weight: 600;
    }
    #ui-datepicker-div{
        width: 90%;
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
    <!--
    <form id="service-data" method="POST" action='./output_excel_report'>
        @csrf
        <input type="text" id="monthpicker" class="date" name="date" value="{{ substr($datas["date"], 0, -3) }}" readonly>
        @if($datas["client"])
        <input type="hidden" name="client_id" value={{ $datas["client"]["id"] ?: -1 }}>
        @else
        <input type="hidden" name="client_id" value=-1>
        @endif
        <input type="hidden" name="facilitie_id" value={{ $datas["facilitie"]["id"] }}>
    </form>
    -->
    @php
        $list_datas = [];
        foreach($datas["clients"] as $client){
            $list_datas[] = [
                "url" => "./service_report_view?facilitie_id=". $datas["facilitie"]["id"] ."&client_id=". $client["id"] ."&date=". $datas["date"],
                // "onclick" => "getExcelReport(".$client['id'].")",
                "img_path" => $client["icon_path"],
                "title" => $client["name"],
                "subtitle" => "サービス提供記録の確認"
            ];
        }
    @endphp
    @component('components.list', ["list_datas" => $list_datas])
    @endcomponent
    <script>
        /* // 月選択は、リンク先のページで
        $("#monthpicker").datepicker({
            dateFormat: 'yy-mm',
            changeMonth: true,
            changeYear: true
        });
        */

        function getExcelReport(id){
            $("input[name='client_id']").val(id);
            $('#service-data').submit();
        }
    </script>
</div>
@endsection
