<section class="input-area d-flex flex-column">
    <style>
        .input-area {
            height: 100%;
        }

        .input-head {
            width: 100%;
            padding: 0px 10px;
            font-size: 15pt;
            color: #fff;
            background-color: var(--linkDarkBlue);
        }
        
        .input-head > a {
            width: 20px;
            height: 100%;
            margin: 0 5px;
            font-weight: bold;
            text-align: center;
            color: #fff;
        }

        .input-head > a:hover {
            text-decoration: none;
        }

        .input-body form {
            width: 92%;
            margin-left: 4%;
            font-size: 15pt;
        }

        .input-body form > * {
            width: 100%;
            display: block;
        }

        .input-body form > p {
            border-bottom: solid 1px var(--linkLightBlue);
            padding-top: 30px;
            color: var(--linkDarkBlue);
        }

        .input-body form input[name="name"] {
            width: 100%;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        .input-body form input[type="submit"] {
            margin-top: 5px;
            padding: 5px;
            text-align: center;
            color: #fff;
            background-color: var(--linkDarkBlue);
            border-radius: 3px;
        }

        .input-body form input[type="submit"]:hover {
            background-color: var(--linkLightBlue);
        }

        /* .input-body table {
            width: 100%;
            margin-top: 10px;
            display: table;
        }

        .input-body table tbody {
            width: 100%;
            position: relative;
        }

        .input-body table tr {
            width: 100%;
            margin-bottom: 5px;
            display: flex;
        }

        .input-body table tr > th, .input-body table tr > td {
            width: 50%;
            font-weight: normal;
        }

        .input-body table tr > td > input {
            width: 99%;
            height: 100%;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        input[name="get_dates[]"] {
            margin-left: 1%;
        } */

        .input-body table {
            width: 100%;
            margin-top: 15px;
            display: table;
            border-collapse: separate;
            border-spacing: 0px 10px;
        }

        .input-body table thead tr {
            display: flex;
            /* margin-bottom: 7px; */
        }

        .input-body table tbody {
            width: 100%;
            position: relative;
        }

        /* .input-body table tbody .separator:nth-of-type(n + 2) {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid var(--linkDarkBlue);
        } */

        .input-body table tr > th {
            font-weight: normal;
        }
        
        .input-body table tr > td {
            width: 100%;
            display: block;
            font-weight: normal;
        }

        .input-body table tr > td > input {
            width: 100%;
            height: 100%;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        .input-body table tr > td > input[name="careers[]"] {
            margin-bottom: 3px;
        }

        @media screen and (min-width: 1000px) {
            .input-body form {
                width: 70%;
                margin-left: 15%;
            }

            /* .input-body table tr > td > input {
                width: 99.5%;
            }

            input[name="get_dates[]"] {
                margin-left: 0.5%;
            } */
        }
    </style>
    <section class="input-head">
        <a href="./home">&lt;</a>
        <span>職員の新規登録</span>
    </section>
    <section class="input-body">
        <form method="POST" action="./add_worker">
            @csrf
            <p>登録情報</p>
            <label>職員の氏名</label>
            <input type="hidden" name="facilitie_id" value="{{ $datas["facilitie"]["id"] }}">
            <input type="text" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
            @error('name')
            <span class="alert-danger small">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
            <table>
                <thead>
                    <tr>
                        <th>経歴・資格／</th>
                        <th>開始・取得日</th>
                    </tr>
                </thead>
                <tbody id="creers-list">
                </tbody>
            </table>
            <input type="submit" value="送信">
        </form>
    </section>
    <script>
        let creersList = document.getElementById("creers-list");
        let creers = document.querySelectorAll("#creers-list > input[name='careers[]']");
        creersChange({value: "aaa"});

        function creersChange(elm){
            if(elm.value!=""){
                let html = '<tr>'
                        //  + '<td><div class="separator"></div></td>'
                         + '<td><input type="text" name="careers[]" onchange="creersChange(this)"></td>'
                         + '<td><input type="date" name="get_dates[]"></td>'
                         + '</tr>';
                $(creersList).append(html);
            }else{
                if(creersList.childElementCount>0){
                    $(elm).parent().parent().remove();
                }
            }
        }
    </script>
</section>