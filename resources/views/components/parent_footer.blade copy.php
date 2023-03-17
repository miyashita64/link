<footer>
    <style>
        footer{
            width: 100%;
            position: fixed;
            font-size: 0;
            bottom: 0;
            left: 0;
            background-color: var(--linkLightBlue);
        }
        footer button{
            width: calc(100%/3);
            header: var(--headerHeight);
            background-color: var(--linkLightBlue);
            border: none;
            text-align: center;
        }
        footer button img{
            height: var(--headerHeight);
        }
    </style>
    <section>
        @if(isset($datas["client"]))
        <a href="./home?client_id={{ $datas["client"]["id"] }}"><button type="button"><img src="../img/logo/book.png"></button></a>
        <a href="./chat_list?client_id={{ $datas["client"]["id"] }}"><button><img src="../img/logo/chat.png"></button></a>
        <a href="./calendar?client_id={{ $datas["client"]["id"] }}"><button type="button"><img src="../img/logo/calendar.png"></button></a>
        @endif
    </section>
</footer>