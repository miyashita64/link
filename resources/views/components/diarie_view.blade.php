<style>
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
    .daily .fname{
        font-size: 15pt;
        color: var(--linkLightBlue);
        text-decoration: underline;
        margin-top: 20px;
    }
    .daily table{
        width: 100%;
        background-color: #eee;
        text-align: center;
    }
    .daily table tr{
        border-bottom: solid 2px #fff;
    }
    .daily table th{
        white-space: nowrap;
    }
    .daily table td{
        border-right: solid 1px #fff;
    }
    .daily table td:nth-child(2){
        min-width: 80px;
    }

    .nothing-msg{
        font-size: 15pt;
        text-align: center;
    }

    .before-button, .next-button{
        width: 10%;
        position: fixed;
        top: 45%;
    }
    .before-button > img, .next-button > img{
        width: 100%;
    }
    .before-button{ left: 0; }
    .next-button{ right: 0; }

    .controll-area{
        width: 90%;
        margin: auto 5%;
        display: flex;
        justify-content: space-between;
    }
    .controll-area > button{
        flex-grow: 1;
        margin: 1%;
        border: solid 5px var(--linkLightBlue);
        border-radius: 25%;
        text-align: center;
    }
    .controll-area > button > img{
        width: 30%;
        padding: 3% 10%;
    }
    .controll-area > button > img:hover{
        opacity: 0.6;
    }

    .ui-area {
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

    .ui-area-invisible-background {
        background-color: rgba(0,0,0,0);
    }

    .ui-area > .form-area{
        width: 80%;
        height: 70%;
        overflow-y: scroll;
        padding: 5%;
        font-size: 13pt;
        background-color: #fff;
    }
    .ui-area > .form-area p{
        width: 50%;
        border-bottom: solid 1px var(--linkLightBlue);
        color: var(--linkDarkBlue);
        word-break: break-all;
    }
    .ui-area > .form-area > p:first-child{
        width: 100%;
        font-size: 15pt;
        padding-left: 10px;
    }
    .img-putter .img-list p {
        margin-bottom: 0;
        font-weight: bold;
        border-bottom: 0;
    }
    .img-putter .img-list img{
        width: 100%;
        margin-bottom: 10px;
        object-fit: cover;
    }
    .img-putter > .form-area > form label span{
        padding: 10px;
        margin-top: 10px;
        display: block;
        color: #fff;
        background-color: var(--linkGreen);
    }
    .img-viewer > img{
        width: 80%;
        height: 70%;
        padding: 2%;
        background-color: #fff;
        object-fit: contain;
    }
    @media screen and (min-width: 1000px) {
        .ui-area > .form-area{
            width: 70%;
            height: 70%;
            overflow-y: scroll;
            padding: 4%;
            font-size: 13pt;
            background-color: #fff;
        }

        .img-putter .img-list{
            width: 50%;
            margin: 0 auto;
        }

        .img-viewer > img{
            width: 70%;
            height: 70%;
            padding: 2%;
            background-color: #fff;
            object-fit: contain;
        }
    }
</style>

<span class="date">{{ $datas["date"] }}</span>
<section class="daily">
    @php
        $diarie_none = true;
    @endphp
    @foreach($datas["diaries"] as $diarie)
    @if(isset($diarie))
    @php
        $diarie_none = false;
    @endphp
    <span class="name">{{ $diarie->getFacilitie()->name }}</span>
    <!-- 全体へのメッセージ -->
    <table>
        <thead>
            <tr><th>時間</th><th>活動</th><th>様子</th></tr>
        </thead>
        <tbody>
            @foreach($diarie->getAlreadyDiarieItems() as $event)
            <tr>
                <td>{{ $event["time"]->format('H:i') }}</td>
                <td>{{ $event["activity"] }}</td>
                <td>{{ $event["comment"] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <p>{{ $diarie["private_msg"] }}</p>
    @endif
    @endforeach

    @if($diarie_none)
    <p class="nothing-msg">{{ $datas["date"] }}の記録はありません</p>
    @endif

    @if(isset($datas["client"]))
    <a href="./home?client_id={{ $datas["client"]["id"] }}&date={{date('Y-m-d',strtotime("$datas[date]"." -1 day"))}}" class="before-button"><img src="../img/logo/left.png"></a>
    <a href="./home?client_id={{ $datas["client"]["id"] }}&date={{date('Y-m-d',strtotime("$datas[date]"." 1 day"))}}" class="next-button"><img src="../img/logo/right.png"></a>
    @else
    <a href="./home?client_id=&date={{date('Y-m-d',strtotime("$datas[date]"." -1 day"))}}" class="before-button"><img src="../img/logo/left.png"></a>
    <a href="./home?client_id=&date={{date('Y-m-d',strtotime("$datas[date]"." 1 day"))}}" class="next-button"><img src="../img/logo/right.png"></a>
    @endif
</section>

@if(!$diarie_none)
<section class="controll-area">
    <button type="button" class="show-img"><img src="../img/logo/image.png"></button>
</section>
@endif

<!-- 画像一覧UI -->
<section class="img-putter ui-area">
    <section class="form-area">
        <p>活動写真</p>
        <!-- 活動写真リスト -->
        <section class="img-list">
        @foreach($datas["diaries"] as $diarie)
            @if(isset($diarie))
            <p>
                <!-- {{ $diarie->getFacilitie()->name }}<br>
                <img src={{ $diarie->getFacilitie()->icon_path }} style="float: left;"> -->
                {{ $diarie->getFacilitie()->name }}
            </p>
            @foreach($diarie->getActiveImage() as $img)
            <img src="{{ $img["path"] }}">
            @endforeach
            @endif
        @endforeach
        </section>
    </section>
</section>

<!-- 画像表示用UI -->
<section class="img-viewer ui-area ui-area-invisible-background" style="z-index: 1;">
    <img>
</section>

<script>
    // 画面クリック時
    $("#content").on("click", function(e){
        // 画像追加ボタンクリックで、画像アップロード画面表示
        if($(e.target).closest(".controll-area .show-img").length){
            // アップロード画面を表示
            $(".img-putter").css("display", "flex");
        }
        // 画像アップロード画面上の画像クリックで、拡大表示
        else if($(e.target).closest(".img-putter .img-list > img").length){
            $(".img-viewer > img").attr("src", e.target.src);
            $(".img-viewer").css("display", "flex");
        }
        // 拡大表示した画像を非表示
        else if($(".img-viewer").css("display")!="none"){
            if(!$(e.target).closest(".img-viewer > img").length){
                $(".img-viewer").css("display", "none");
            }
        }
        // アップロード画面外をクリック時、アップロード画面を非表示
        else if(!$(e.target).closest(".img-putter > .form-area").length){
            $(".img-putter").css("display", "none");
        }
    });
</script>
