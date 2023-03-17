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

        .input-body form input[type="text"] {
            width: 100%;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        .input-body form input[type="date"] {
            width: 100%;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        .input-body form input[type="submit"] {
            margin-top: 15px;
            padding: 5px;
            text-align: center;
            color: #fff;
            background-color: var(--linkDarkBlue);
            border-radius: 3px;
        }

        .input-body form input[type="submit"]:hover {
            background-color: var(--linkLightBlue);
        }

        @media screen and (min-width: 1000px) {
            .input-body form {
                width: 70%;
                margin-left: 15%;
            }
        }
    </style>
    <section class="input-head">
        <a href="./home">&lt;</a>
        <span>施設利用者の新規登録</span>
    </section>
    <section class="input-body">
        <form method="POST" action="./add_client">
            @csrf
            <p>登録情報</p>
            <input type="hidden" name="facilitie_id" value="{{ $datas["facilitie"]["id"] }}">
            <label>利用者の名前
                <input type="text" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                @error('name')
                    <span class="alert-danger small">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </label>
            <label>生年月日
                <input type="date" name="birthday" value="{{ old('birthday') }}" required autocomplete="birthday" autofocus>
                @error('birthday')
                    <span class="alert-danger small">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
             </label>
            <label>受給者番号
                <input type="text" name="benefic_num" value="{{ old('benefic_num') }}" required autocomplete="benefic_num" autofocus id="date_input">
                @error('benefic_num')
                    <span class="alert-danger small">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </label>
            <label>学校の名前
                <input type="text" name="school_name" value="{{ old('school_name') }}" required autocomplete="school_name" autofocus>
                @error('school_name')
                    <span class="alert-danger small">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </label>
            <input type="submit" value="送信">
        </form>
    </section>
</section>