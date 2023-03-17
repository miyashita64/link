<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style type="text/css">
        @font-face {
            font-family: ipag;
            font-style: normal;
            font-weight: normal;
            src: url('{{ storage_path('fonts/migu-1p-regular.ttf') }}') format('truetype');
        }

        @font-face {
            font-family: ipag;
            font-style: bold;
            font-weight: bold;
            src: url('{{ storage_path('fonts/migu-1p-regular.ttf') }}') format('truetype');
        }

        body {
            font-family: ipag !important;
        }
    </style>
    <style>
        body{
            width: 100%;
        }
        .date{
            width: 100%;
            display: inline-block;
            text-align: center;
            font-size: 20pt;
            font-weight: 600;
        }
        .daily{
            border: solid 5px var(--linkLightBlue);
            border-radius: 10px;
            margin: 10px 5%;
            padding: 10px;
        }
        .daily .cname{
            font-size: 15pt;
            color: var(--linkLightBlue);
            text-decoration: underline;
            margin-top: 20px;
        }
        .daily table{
            width: 100%;
            margin-top: 10px;
            background-color: #eee;
            text-align: center;
            border-collapse: collapse;
        }
        .daily .facilitie-comment{
            background-color: #fff;
            text-align: left;
        }
        .daily table tr{
            border-bottom: solid 2px #fff;
        }
        .daily table th{
            white-space: nowrap;
        }
        .daily table td{
            border-right: solid 1px #fff;
            word-break: break-all;
            word-wrap: break-word;
        }
        .daily table td:nth-child(2){
            min-width: 80px;
        }

        .img-list{
            width: 100%;
            margin: 10px 5%;
            padding: 10px;
        }
        .img-pare{
            display: block;
            width: 100%;
            padding: 0;
            margin: 0;
            clear: left;
        }
        .img-list .img-pare img{
            width: 44%;
            margin: 1%;
            padding: 2%;
            background-color: #eee;
            float: left;
        }
        
        .nothing-msg{
            font-size: 15pt;
            text-align: center;
        }
    </style>
</head>
<body>
    @if(!$datas["diarie"])
    <p class="daily" style="text-align: center; margin-top: calc(var(--headerHeight) + 20px);">本日の利用予定はありません</p>
    @else
    
    <!-- 記入済みレコード表示 -->
    <section class="diarie-section">
        <!-- 日付 -->
        <span class="date">{{ $datas["date"] }}</span>
        <!-- 連絡帳閲覧テーブル -->
        <section class="daily">
            <!-- 利用者名 -->
            <span class="cname">{{ $datas["clients"]->find($datas["client"]["id"])["name"] }}</span>
            <!-- 全体へのメッセージ -->
            <!-- 既存のレコード表示欄 -->
            @if($datas["diarie"])
            <table>
                <thead>
                    <tr><th>時間</th><th>活動</th><th>様子</th></tr>
                </thead>
                <!-- 既存のレコード -->
                <tbody class="viewer">
                    @foreach($datas["diarie"]["items"] as $event)
                    <tr>
                        <td>{{ $event["time"]->format('H:i') }}</td>
                        <td>{{ $event["activity"] }}</td>
                        <td>{{ $event["comment"] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <table class="facilitie-comment">
                <thead>
                    <tr>
                        <th>施設からのコメント</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $datas["diarie"]["private_msg"] }}</td>
                    </tr>
                </tbody>
            </table>
            @endif
        </section>
    </section>
    
    <section class="img-list">
        @foreach($datas["active_imgs"] as $key => $img)
        @if($key%2 == 0)
        <section class="img-pare">
        @endif
            <img src="{{ public_path("img/".$img["path"]) }}">
        @if($key%2 == 1)
        </section>
        @endif
        @endforeach
    </section>
    
    @endif

</body>
</html>