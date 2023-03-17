@extends('layouts.teacher', ["datas" => $datas])

@section('styles')
<style>
#content{
    margin: calc(var(--headerHeight80pxVer) + 20px) 3% 100px;
}
#search-form{
    font-size: 14pt;
}
#search-form > section{
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 90%;
    margin: 0 auto;
}
#search-form > section > p:first-child{
    align-self: left;
    color: var(--linkGreen);
    font-size: 15pt;
    text-decoration: underline;
    padding: 0;
    margin: 0;
}
#search-form > section > section,
#search-form > section > section > label{
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
}
#search-form > section > section > label > span{
    padding: 0 10px;
}
#search-form input,
#search-form select{
    flex-grow: 1;
    min-height: 30px;
    text-align: center;
    background-color: #eee;
    border-bottom: solid 1px #eee;
}
#search-form input[type="button"]{
    text-align: center;
    color: var(--linkWhite);
    background-color: var(--linkDarkBlue);
    padding: 10px;
    margin: 10px 0;
}
</style>
@endsection

@section('content')
<div id="content">
    <form id="search-form" method="POST" action="../display">
        @csrf
        <section>
            <p>検索期間</p>
            <section>
                <label>
                    <span>開始</span>
                    <input type="date" name="start_date" value="{{ (new DateTimeImmutable)->modify('first day of')->format('Y-m-d') }}">
                </label>
                <span>〜</span>
                <label>
                    <span>終了</span>
                    <input type="date" name="end_date" value="{{ (new DateTimeImmutable)->modify('last day of')->format('Y-m-d') }}">
                </label>
            </section>
        </section>
        <section>
            <p>検索単位</p>
            <section>
                <label>
                    <select name="unit">
                        <option value="date">日</option>
                        <option value="month" selected>月</option>
                        <option value="year">年度</option>
                    </select>
                </label>
            </section>
        </section>
        <section>
            <p>検索項目</p>
            <section>
                <label>
                    <input type="text" name="activity">
                </label>
            </section>
        </section>
        <section>
            <p>検索対象</p>
            <section>
                <label>
                    <span>入学年度</span>
                    <select type="text" name="entered_at">
                        <option value="0000-00-00">全在校生</option>
                        @foreach($datas["year_list"] as $year)
                        <option value="{{ $year["value"] }}">{{ $year["content"] }}</option>
                        @endforeach
                    </select>
                </label>
            </section>
        </section>
        <section>
            <section>
                <input type="button" value="集計" onclick="sendSearchData()">
            </section>
        </section>
    </form>
    <section id="graph-area"></section>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0/dist/Chart.min.js"></script>
<script>
    var graph_datas = @json($datas["graph_datas"]);
    var graph_area = document.getElementById("graph-area");
    var charts = [];

    // graph_datasをグラフに反映
    function updateGraphs(){
        graph_area.innerHTML = "";
        graph_datas.forEach((graph_data)=>{
            let graph_keys = Object.keys(graph_data.data);
            let graph_values = graph_keys.map((key)=>graph_data.data[key]);
            let elm = document.createElement("canvas");
            let ntx = elm.getContext('2d');
            charts.push(
                new Chart(ntx, {
                    type: "line",
                    data: {
                        labels: graph_keys,
                        datasets: [{
                            label: graph_data.label,
                            data: graph_values,
                            backgroundColor: 'rgb(255, 99, 132)',
                            borderColor: 'rgb(255, 99, 132)',
                            fill: false,        // 背景色の無効化
                            lineTension: 0,     // 線を直線にする
                        }]
                    },
                    option: {}
                })
            );
            graph_area.appendChild(elm);
        });
    }

    // 検索条件を送信
    function sendSearchData(){
        var searchFormData = new FormData(document.getElementById("search-form"));
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "./search_school_item",
            type: "POST",
            data: searchFormData,
            dataType: "text",
            async: false,
            cache: false,
            contentType: false,
            processData: false,
        }).done(function (data) {
            console.log(data);
            graph_datas = JSON.parse(data);
            updateGraphs();
        }).fail(function (e) {
            alert("送信失敗...");
            console.log(e);
        });
    }
</script>
@endsection
