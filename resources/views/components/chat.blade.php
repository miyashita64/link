<section class="chat-area d-flex flex-column">
    <style>
        .chat-area{
            height: 100%;
        }
        .chat-head{
            width: 100%;
            padding: 0px 10px;
            font-size: 15pt;
            color: #fff;
            background-color: var(--linkDarkBlue);
        }
        
        .chat-head > a{
            width: 40px;
            height: 100%;
            display: inline-block;
            text-align: center;
            color: #fff;
        }
        .chat-head > a:hover{
            color: var(--linkDarkBlue);
            background-color: #fff;
            text-decoration: none;
        }
        
        .chat-body{
            overflow-y: scroll;
            padding: 0 5%;
        }
        .date > p:first-child{
            color: #777;
            padding: 10px;
            border-top: solid 1px #ddd;
            border-bottom: solid 1px #ddd;
            text-align: center;
        }
        .own-message, .other-message{
            width: 100%;
            margin: 20px 0;
            display: flex;
            align-items: center;
        }
        .own-message{ flex-direction: row-reverse; }
        .other-message{ flex-direction: row; }
        .own-message > img, .other-message > img{
            width: 50px;
            height: 50px;
            padding: 0;
            border: solid 3px #aaa;
            border-radius: 30%;
            position: relative;
        }
        
        .balloon{
            position: relative;
            display: inline-block;
            border-radius: 5px;
            padding: 7px 10px;
            margin: 0 15px;
            max-width: 100%;
            color: #555;
            font-size: 16px;
            background: #e0edff;
            table-layout: fixed;
        }
        .balloon p{
            margin: 0;
            padding: 0;
            overflow-wrap : break-word;
        }
        .balloon + span{
            color: #aaa;
            align-self: flex-end;
            white-space: nowrap;
        }
        .own-message .balloon::before{
            content: "";
            position: absolute;
            top: 50%;
            left: 100%;
            margin-top: -15px;
            border: 15px solid transparent;
            border-left: 15px solid #e0edff;
        }
        .other-message .balloon::before{
            content: "";
            position: absolute;
            top: 50%;
            left: -30px;
            margin-top: -15px;
            border: 15px solid transparent;
            border-right: 15px solid #e0edff;
        }
        
        .chat-foot{
            width: 100%;
        }
        .chat-foot > form{
            width: 100%;
            display: flex;
            font-size: 12pt;
            align-items: stretch;
        }
        .chat-foot > form > textarea{
            flex-grow: 1;
            resize: none;
            padding: 10px;
            border: solid 3px var(--linkDarkBlue);
            font-size: 100%;
        }
        .chat-foot > form > input[type="submit"]{
            padding: 10px;
            font-size: 100%;
            color: #fff;
            background-color: var(--linkDarkBlue);
            border: solid 3px var(--linkDarkBlue);
            border-left: none;
        }
        .chat-foot > form > input[type="submit"]:hover{
            background-color: var(--linkDarkBlue);
        }
    </style>

    <section class="chat-head">
        @php
            $url_path = "./chat_list";
            if($datas["client"]){
                $url_path .= "?client_id=". $datas["client"]["id"];
                if($datas["facilitie"]){
                    $url_path .= "&facilitie_id=".$datas["facilitie"]["id"];
                }
            }else if($datas["facilitie"]){
                $url_path .= "?facilitie_id=".$datas["facilitie"]["id"];
            }
        @endphp
        <a href="{{$url_path}}">&lt;</a>
        <span>{{ $datas["chats"]["other_name"] }}</span>
    </section>
    <section class="chat-body flex-grow-1">
        @foreach ($datas["chats"]["chats"] as $chat)
        <section id={{ $chat["date"] }} class="date">
            <p>{{ $chat["date"] }}</p>
            @foreach ($chat["messages"] as $msg)
            @if($msg["send_fg"])
            <section class="own-message">
                <img src={{ $datas["chats"]["own_icon"] }}>
            @else
            <section class="other-message">
                <img src={{ $datas["chats"]["other_icon"] }}>
            @endif
                <section class="balloon">
                    <p>
                        {!! nl2br(htmlspecialchars($msg["text"])) !!}
                    </p>
                </section>
                <span>
                    @if($msg["send_fg"] && $msg["readed"])
                    既読<br>
                    @endif
                    {{ $msg["time"] }}
                </span>
            </section>
            @endforeach
        </section>
        @endforeach
    </section>
    <section class="chat-foot">
        <form method="POST" action="./chat">
            @csrf
            <input type="hidden" name="to_id" value="{{ $datas["chats"]["other_id"] }}">
            @if($datas["client"])
            <input type="hidden" name="client_id" value="{{ $datas["client"]["id"] ?: null }}">
            @else
            <input type="hidden" name="client_id" value=null>
            @endif
            <input type="hidden" name="facilitie_id" value="{{ isset($datas["facilitie"])? $datas["facilitie"]["id"] : null }}">
            <textarea name="body" placeholder="メッセージを入力してください"></textarea>
            <input type="submit" value="送信" />
        </form>
    </section>
</section>

<script>
    $(function(){
        $scrollAuto = $(".chat-body");
        $scrollAuto.animate({scrollTop: $scrollAuto[0].scrollHeight}, 1);
    });
</script>