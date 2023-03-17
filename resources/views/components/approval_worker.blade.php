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
        <span>新規職員の承認</span>
    </section>
    <section class="profile-body">
        <form method="POST" action="./approval_worker" enctype="multipart/form-data">
            @csrf
            <p>雇用施設</p>
            <label>選択施設</label>
            <select id="facilitie-list" name="facilitie_id" onchange="updateWorkerList(this.value)">
                @foreach($datas["facilities"] as $facilitie)
                <option value="{{ $facilitie["id"] }}">{{ $facilitie["name"] }}</option>
                @endforeach
            </select>
            <p>職員選択</p>
            <label>選択職員</label>
            <select id="worker-list" name="worker_id"></select>
            <p>職員プロフィール<input type="submit" value="承認" onclick="alertAndSubmit(event)"></p>
            <input type="hidden" name="worker_user_id" value="{{ $datas["worker"]["id"] }}">
            <label>職員の名前</label>
            <input type="text" class="input-readonly" value="{{ $datas["worker"]["name"] }}" readonly>
            <label>職員のアイコン</label>
            <img src="{{ $datas["worker"]["icon_path"] }}">
        </form>
    </section>
    <script src="{{ mix('js/original_alert.js') }}"></script>
    <script>
        let workerList = $("#worker-list");
        let facilities = @json($datas["facilities"]);
        document.getElementById("facilitie-list").onchange();

        function updateWorkerList(facilitie_id){
            let html = "";
            for(let worker of facilities[facilitie_id]["workers"]){
                if(worker["user"]) continue;
                html += '<option value="'+worker["id"]+'">'+worker["name"]+'</option>';
            }
            if(html=="") html = "<option>選択可能な職員が存在しません</option>";
            workerList.html(html);
        }
    </script>
</section>