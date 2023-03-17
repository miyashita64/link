<section class="calendar-area">
    <style>
        .calendar-area {
            height: 100%;
            margin: 0;
        }

        .calendar-area .calendar-title {
            width: 100%;
            height: 50px;
            margin: 0 auto;
            padding: 10px 0;
            display: block;
            text-align: center;
            font-size: 23pt;
            color: #fff;
            background-color: var(--linkDarkBlue);
        }

        .calendar-area .calendar-container {
            width: 100%;
            height: calc(100% - 60px);
            margin: 0 auto;
        }

        #js-pre-button, #js-next-button {
            width: 10%;
            position: fixed;
            top: 45%;
        }
        #js-pre-button{ left: 0; }
        #js-next-button{ right: 0; }

        .calendar-area .calendar-table {
            width: 80%;
            height: 100%;
            margin: 0 10%;
        }
        .calendar-area .calendar-table th {
            width: calc(100%/7);
            height: 30px;
            text-align: center;
            font-size: 20pt;
            line-height: 50px;
            color: #8b8;
            background-color: #eff;
        }
        .calendar-area .calendar-table td {
            height: calc((100%)/ 6);
            border: solid 1px #000;
            text-align: center;
            vertical-align: top;
            font-size: 20pt;
            
        }
        .calendar-area .calendar-table tr > *:first-child { color: #f00; }
        .calendar-area .calendar-table tr > *:last-child { color: #00f; }
        .calendar-area .calendar-table .no-tomonth{ opacity: 0.3; }
        .calendar-area .calendar-table a {
            display: inline-block;
            width: 100%;
            height: 100%;
        }
        .calendar-area .calendar-table a:hover { background-color: #ffa; }
        .calendar-area .calendar-table .is-today { background-color: #cfc; }
    </style>

    <h2 class="calendar-title">
      <span id="js-year"></span>年 <span id="js-month"></span>月
    </h2>

    <div class="calendar-container">
      <table class="calendar-table">
        <thead>
          <tr><th>日</th><th>月</th><th>火</th><th>水</th><th>木</th><th>金</th><th>土</th></tr>
        </thead>
        <tbody id="js-calendar-body"></tbody>
      </table>
    </div>

    <img id="js-pre-button" src="../img/logo/left.png" alt="前の月へ移動">
    <img id="js-next-button" src="../img/logo/right.png" alt="次の月へ移動">

    <script>
        let $window = $(window);
        let $body = $("body");
        let $year = $('#js-year');
        let $month = $('#js-month');
        let $tbody = $('#js-calendar-body');
        let $prebutton = $('#js-pre-button');
        let $nextbutton = $('#js-next-button');

        let today = new Date();
        let currentYear = today.getFullYear();
        let currentMonth = today.getMonth();

        //カレンダー表示
        $window.on('load',function(){
            //*/
            calendarHeading(currentYear, currentMonth);
            calendarBody(currentYear, currentMonth, today);
            /*// リロード時に値を保持する
            if(sessionStorage.getItem("storageYear") && sessionStorage.getItem("storageMonth")){ //リロード時
                currentYear = parseInt(sessionStorage.getItem("storageYear"));
                currentMonth = parseInt(sessionStorage.getItem("storageMonth"));
                calendarHeading(currentYear, currentMonth);
                calendarBody(currentYear, currentMonth, today);
            }else{  //最初に実行
                calendarHeading(currentYear, currentMonth);
                calendarBody(currentYear, currentMonth, today);
            }
            //*/
        });

        //ボタンクリックで前の月のカレンダー表示
        $prebutton.on('click',function(){
            currentYear = (currentMonth === 0) ? currentYear - 1 : currentYear;
            currentMonth = (currentMonth === 0) ? 11 : currentMonth - 1;
            calendarHeading(currentYear, currentMonth);
            calendarBody(currentYear, currentMonth, today);
            //更新時にもデータを維持
            sessionStorage.setItem('storageYear', currentYear);
            sessionStorage.setItem('storageMonth', currentMonth);
        });

        //ボタンクリックで次の月のカレンダー表示
        $nextbutton.on('click',function(){
            currentYear = (currentMonth === 11) ? currentYear + 1 : currentYear;
            currentMonth = (currentMonth + 1) % 12;
            calendarHeading(currentYear, currentMonth);
            calendarBody(currentYear, currentMonth, today);
            //更新時にもデータを維持
            sessionStorage.setItem('storageYear', currentYear);
            sessionStorage.setItem('storageMonth', currentMonth);
        });

        //表のヘッダに年月表示
        function calendarHeading(year, month){
            $year.text(year);
            $month.text(month + 1);
        }

        //日付テーブル表示
        function calendarBody(year, month, today){
            let todayYMFlag = today.getFullYear() === year && today.getMonth() === month ? true : false; // 本日の年と月が表示されるカレンダーと同じか判定
            let startDate = new Date(year, month, 1); // その月の最初の日の情報
            let endDate  = new Date(year, month + 1 , 0); // その月の最後の日の情報
            let preEndDate = new Date(year, month, 0); // 前月の最後の日の情報
            let startDay = startDate.getDay();// その月の最初の日の曜日を取得
            let endDay = endDate.getDate();// その月の最後の日にちを取得
            let preEndDay = preEndDate.getDate(); //その月の最後の日にちを取得
            let textDate = 1; // 日付
            let tableBody = ''; // テーブルのHTMLを格納する変数
            let textTd = ''; //表示する日付

            //カレンダーの行
            for(let row = 0; row < 6; row++){
                let tr = '<tr>';
                //カレンダーの列
                for(let col = 0; col < 7; col++){
                    let dy = 0;
                    let dm = 0;
                    if(row === 0 && col < startDay){
                        textTd = preEndDay - startDay + col + 1;
                        if(month==0){
                            dy = -1;
                            dm = 11;
                        }else{
                            dm = -1;
                        }
                    }else if(textDate > endDay){
                        textTd = textDate - endDay;
                        textDate++;
                        if(month==11){
                            dy = 1;
                            dm = -11;
                        }else{
                            dm = 1;
                        }
                    }else{
                        textTd = textDate++;
                        noToMonthFlag = false;
                    }
                    let addClass = todayYMFlag && textDate-1 === today.getDate() ? 'is-today'
                                : dy+dm!=0? 'no-tomonth'
                                : '';
                    let url = './home?client_id={{ $datas["client"]["id"] }}&date='+(year+dy)+'-'+(month+dm+1)+'-'+textTd;
                    let td = '<td class="'+addClass+'"><a href="'+url+'">'+textTd+'</a></td>';
                    tr += td;
                }
                tr += '</tr>';
                tableBody += tr;
            }
            $tbody.html(tableBody).trigger("create");
        }
    </script>
</section>