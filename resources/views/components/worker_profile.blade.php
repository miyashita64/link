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

        .profile-body form > p {
            border-bottom: solid 1px var(--linkLightBlue);
            padding-top: 30px;
            color: var(--linkDarkBlue);
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

        .profile-body form > input[type="text"] {
            width: 100%;
            margin-bottom: 10px;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        .profile-body form > img {
            width: 80px;
            height: 80px;
            margin-top: -80px;
            padding: 0;
            border: solid 3px #aaa;
            border-radius: 10px;
            object-fit: cover;
        }

        .icon-change-cover {
            width: 80px!important;
            height: 80px;
            position: relative;
            line-height: 80px;
            text-align: center;
            text-shadow: 0 0 2px black;
            color: #fff;
            background: rgba(170, 170, 170, 0.5);
            border: solid 3px #aaa;
            border-radius: 10px;
        }

        @media screen and (min-width: 1000px) {
            .profile-body form {
                width: 70%;
                margin-left: 15%;
            }
        }
    </style>
    <div id="original_alert">更新しました<span>&#10003;</span></div>
    <section class="profile-head">
        <a href="./home">&lt;</a>
        <span>プロフィールの設定</span>
    </section>
    <section class="profile-body">
        <form method="POST" action="./update_worker" enctype="multipart/form-data">
            @csrf
            <p>職員のプロフィール<input type="submit" value="更新" onclick="alertAndSubmit(event)"></p>
            <input type="hidden" name="id" value="{{ Auth::id() }}">
            <label>名前</label>
            <input type="text" name="name" value="{{ Auth::user()["name"] }}">
            <label>携帯番号</label>
            <input type="text" name="tel" value="{{ Auth::user()["tel"] }}">
            <label>アイコン</label>
            <div class="icon-change-cover">変更</div>
            <input type="file" name="icon_img" style="width: 80px; height: 80px; opacity: 0; margin-top: -80px; cursor: pointer;" accept="image/*">
            <img src="{{ Auth::user()["icon_path"] }}">
        </form>
    </section>
    <script src="{{ mix('js/original_alert.js') }}"></script>
    <script>
        $('.profile-body > form input[name="icon_img"]').on('change', function(e){
            var reader = new FileReader();
            reader.onload = function(e2){
                console.log(e.target.nextElementSibling);
                e.target.nextElementSibling.src = e2.target.result;
            }
            reader.readAsDataURL(e.target.files[0]);
        });
    </script>
</section>
