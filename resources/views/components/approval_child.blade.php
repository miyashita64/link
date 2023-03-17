<section class="profile-area d-flex flex-column">
    <style>
        .profile-area {
            height: 100%;
        }

        #original_alert {
            width: 90%;
            max-width: 360px;
            margin: 0 auto;
            padding: 10px;
            display: none;
            position: fixed;
            top: 15px;
            left: 0;
            right: 0;
            font-size: 15pt;
            color: #fff;
            background-color: var(--linkDarkBlue);
            border-radius: 3px;
            box-shadow: 0 0 3px 0 #104271;
            z-index: 10;
        }

        #original_alert > span {
            float: right;
        }
        
        .profile-head {
            width: 100%;
            padding: 0px 10px;
            font-size: 15pt;
            color: #fff;
            background-color: var(--linkDarkBlue);
        }
        
        .profile-head > a {
            width: 40px;
            height: 100%;
            display: inline-block;
            text-align: center;
            color: #fff;
        }

        .profile-head > a:hover {
            text-decoration: none;
        }

        .profile-body form {
            width: 92%;
            margin-left: 4%;
            font-size: 15pt;
        }

        .profile-body form > * {
            width: 100%;
            display: block;
        }
        
        .profile-body form select {
            width: 100%;
            margin-bottom: 10px;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        .profile-body form > p {
            border-bottom: solid 1px var(--linkLightBlue);
            padding-top: 30px;
            color: var(--linkDarkBlue);
        }

        .profile-body form input[type="text"] {
            width: 100%;
            margin-bottom: 15px;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        .profile-body form .input-readonly {
            background-color: #eee;
        }

        .profile-body form > p > input[type="submit"] {
            display: inline-block;
            height: 30px;
            float: right;
            border: solid 1px var(--linkDarkBlue);
            padding: 0 20px;
            font-size: 20px;
            margin-top: -3px;
        }

        .profile-body form > p > input[type="submit"]:hover {
            color: #fff;
            background-color: var(--linkDarkBlue);
        }

        .profile-body form label {
            margin-bottom: 0;
        }

        .profile-body form > img {
            width: 80px;
            height: 80px;
            padding: 0;
            border: solid 3px #aaa;
            border-radius: 10px;
            object-fit: cover;
        }
        
        @media screen and (min-width: 1000px) {
            .profile-body form {
                width: 70%;
                margin-left: 15%;
            }
        }
    </style>
    <div id="original_alert">承認しました<span>&#10003;</span></div>
    <section class="profile-head">
        <a href="./home">&lt;</a>
        <span>児童の承認</span>
    </section>
    <section class="profile-body">
        <form method="POST" action="./approval_child" enctype="multipart/form-data">
            @csrf
            <p>利用施設</p>
            <label>施設選択</label>
            <select id="facilitie-list" name="facilitie_id" onchange="updateClientList(this.value)">
                @foreach($datas["facilities"] as $facilitie)
                <option value="{{ $facilitie["id"] }}">{{ $facilitie["name"] }}</option>
                @endforeach
            </select>
            <p>施設利用者</p>
            <label>利用者選択</label>
            <select id="client-list" name="client_id"></select>
            <p>児童のプロフィール<input type="submit" value="承認" onclick="alertAndSubmit(event)"></p>
            <input type="hidden" name="child_id" value="{{ $datas["child"]["id"] }}">
            <label>児童の名前</label>
            <input type="text" class="input-readonly" value="{{ $datas["child"]["name"] }}" readonly>
            <label>児童のアイコン</label>
            <img src="{{ $datas["child"]["icon_path"] }}">
        </form>
    </section>
    <script src="{{ mix('js/original_alert.js') }}"></script>
    <script>
        let clientList = $("#client-list");
        let facilities = @json($datas["facilities"]);
        document.getElementById("facilitie-list").onchange();

        function updateClientList(facilitie_id){
            let html = "";
            console.log(facilities);
            for(let client of facilities[facilitie_id]["clients"]){
                let cname = (client["child_id"]==null)? client["name"] : client["name"]+"(認証済み)";
                html += '<option value="'+client["id"]+'">'+cname+'</option>';
            }
            if(html=="") html = "<option>選択可能な職員が存在しません</option>";
            clientList.html(html);
        }
    </script>
</section>