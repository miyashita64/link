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
        <span>子供の新規登録</span>
    </section>
    <section class="input-body">
        <form method="POST" action="./add_child">
            @csrf
            <p>登録情報</p>
            <label>名前
                <input type="text" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                @error('name')
                    <span class="alert-danger small">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </label>
            <input type="submit" value="送信">
        </form>
    </section>
</section>