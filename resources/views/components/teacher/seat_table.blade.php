<section class="seat-table">
    <style>
        .seat-table-area{
            padding: 0 30px 20px;
        }
        .seat-table-row{
            display: flex;
            justify-content: space-around;
        }
        .seat-list-row{
            margin: 0;
        }
        .seat-list-row:first-of-type{
            display: none;
        }
        .seat{
            display: flex;
            justify-content: center;
            align-items: stretch;
            border-radius: 10%;
        }
        .seat > input[type="checkbox"]{
            display: none;
        }
        .seat-table-row .seat{
            width: calc(90% / {{ $datas["classroom"]["column_size"] }});
            margin: 10px;
            border: solid 2px var(--linkDarkBlue);
        }
        .seat-list-row .seat{
            margin: 0;
        }
        .seat-list-row .seat > input[type="checkbox"][value=""] + span:empty{
            display: none;
        }
        .seat > span{
            display: flex;
            flex-grow: 1;
            justify-content: center;
            align-items: center;
            height: 50px;
            font-size: 13pt;
            word-break: break-all;
            line-height: 100%;
        }
        .seat-table-row > .seat > span{
            border-radius: 10%;
        }
        .seat > input[type="checkbox"]:checked + span{
            background-color: var(--linkLightBlue);
        }
    </style>
    <section class="seat-table-area">
        <p class="seat-table-row">
            <label class="seat"><span>教卓</span></label>
        </p>
        @for($row = 0; $row < $datas["classroom"]["row_size"]; $row++)
        <p class="seat-table-row">
            @for($col = 0; $col < $datas["classroom"]["column_size"]; $col++)
            <label class="seat">
                @if(array_key_exists($row, $datas["seats"]))
                @if(array_key_exists($col, $datas["seats"][$row]))
                <input type="checkbox" name="student_ids[]" value="{{ $datas["seats"][$row][$col]["id"] }}">
                <span>{{ $datas["seats"][$row][$col]["name"] }}</span>
                @else
                <input type="checkbox" name="student_ids[]" value="">
                <span></span>
                @endif
                @else
                <input type="checkbox" name="student_ids[]" value="">
                <span></span>
                @endif
            </label>
            @endfor
        </p>
        @endfor
    </section>
</section>