<section class="list">
    <style>
        .list > .list-item{
            display: flex;
            align-items: center;
            width: 100%;
        }
        .list > .list-item:hover{
            background-color: #eee;
        }
        .list > .list-item > .icon-area{
            display: flex;
        }
        .list > .list-item > .icon-area > img{
            align-self: center;
            width: 80px;
            margin: 5px;
            padding: 8px;
            border: solid 3px #aaa;
            border-radius: 30%;
        }
        .list > .list-item > .icon-area > .number-area{
            position: relative;
            width: 0;
        }
        .list > .list-item > .icon-area > .number-area > .number-icon{
            display: flex;
            justify-content: center;
            align-items: center;
            position: absolute;
            top: 0;
            right: 0;
            width: 30px;
            height: 30px;
            color: #fff;
            background-color: #f00;
            border-radius: 50%;
        }
        .list > .list-item > .text-area{
            flex-grow: 1;
            padding: 5px;
        }
        .list > .list-item > .text-area > .list-item-main{
            display: flex;
            justify-content: space-between;
        }
        .list > .list-item > .text-area > .list-item-main > .list-item-title{
            margin: 0;
            color: var(--linkGreen);
            font-size: 18pt;
            font-weight: 600;
        }
        .list > .list-item > .text-area > .list-item-main > .list-item-note{
            color: #aaa;
        }
        .list > .list-item > .text-area > .list-item-subtitle{
            height: 2.5em;
            margin: 3px;
            overflow-y: hidden;
        }
    </style>
    @foreach($list_datas as $ldata)
    <a @if(isset($ldata["url"])) href="{{ $ldata["url"] }}" @endif @if(isset($ldata["onclick"])) onclick="{{ $ldata["onclick"] }}" @endif class="list-item">
        <section class="icon-area">
            <img src="{{ $ldata["img_path"] }}">
            @if(isset($ldata["number"]))
            @if($ldata["number"]>0)
            <span class="number-area">
                <span class="number-icon"><span>{{ $ldata["number"] }}</span></span>
            </span>
            @endif
            @endif
        </section>
        <section class="text-area">
            <section class="list-item-main">
                <span class="list-item-title">{{ $ldata["title"] }}</span>
                @if(isset($ldata["note"]))
                <span class="list-item-note">{{ $ldata["note"] }}</span>
                @endif
            </section>
            @if(isset($ldata["subtitle"]))
            <p class="list-item-subtitle">{{ $ldata["subtitle"] }}</p>
            @endif
        </section>
    </a>
    @endforeach
</section>