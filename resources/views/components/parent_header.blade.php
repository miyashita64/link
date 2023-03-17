<header class="d-flex align-items-center">
  <style>
    header {
      width: 100%;
      height: var(--headerHeight80pxVer);
      position: fixed;
      top: 0;
      left: 0;
      z-index: 10;
      background-color: var(--linkLightBlue);
    }

    header > h1 {
      white-space: nowrap;
      margin: 0;
      color: var(--linkGreen);
    }

    header > button {
      width: var(--headerHeight);
      height: var(--headerHeight);
      margin: 0 10px;
      position: relative;
      background: none;
      border: none;
    }

    header > button:focus {
      outline: 5px auto #047cbc;
    }

    header > button > img {
      width: 100%;
    }

    header > button > span {
      white-space: nowrap;
      position: absolute;
      left: calc(var(--headerHeight) + 3px);
      bottom: -10px;
      max-width: 70px;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    header > section {
      width: 50%;
      display: none;
      position: absolute;
      top: 100%;
      overflow: hidden;
      background-color: #adf;
    }

    header > section > a {
      width: 100%;
      padding: 10px;
      display: flex;
      justify-content: start;
      align-items: center;
    }

    header > section > a:hover {
      text-decoration: none;
      background-color: #fff;
      cursor: pointer;
    }

    header > section > a > img {
      width: calc(var(--headerHeight) * 0.9);
      height: calc(var(--headerHeight) * 0.9);
      margin-right: 10px;
      object-fit: cover;
    }

    header > section > a > span {
      word-break: break-all;
    }

    @if(isset($datas["child"]))

    /* header > #qrcode {
      width: 100%;
      max-width: 100%;
      display: none;
      background-color: rgba(0,0,0,0.3);
    } */

    header > #qrcode {
      width: 100%;
      max-width: 100%;
      display: none;
      background-color: rgba(0,0,0,0.5);
    }

    @media (orientation: portrait) {
      header > #qrcode > canvas {
        width: 60%;
      }
    }

    @media (orientation: landscape) {
      header > #qrcode > canvas {
        height: 60%;
      }
    }

    header > #qrcode > canvas {
      padding: 10px;
      background-color: var(--linkLightBlue);
      z-index: 10;
    }

    header > #qrcode::after {
      content: "{{ $datas["child"]["name"] }}のQRコード";
      margin: 30px auto;
      display: inline-block;
      position: absolute;
      top: 20px;
      left: auto;
      font-weight: bold;
      font-size: calc(var(--headerHeight)*0.7);
      color: #fff;
      text-shadow: 0 0 3px black;
      z-index: 9;
    }

    @endif

    .accounts-area {
      left: 0;
      box-shadow: 0 0 3px 0 #047cbc;
    }

    .settings-area {
      right: 0;
      box-shadow: 0 0 3px 0 #047cbc;
    }

    .selected-client {
      background-color: var(--linkLightBlue);
    }

    @media screen and (min-width: 1000px) {
      header > section {
        width: 500px;
      }
    }
  </style>
  <button type="button" class="accounts-toggle">
    <img src="../img/logo/accounts.png" />
    @if(isset($datas["child"]))
      <span>{{ $datas["child"]->name }}</span>
    @endif
  </button>
  <section class="accounts-area">
    @if(isset($datas["child"]))
      @foreach($datas["children"] as $child)
        <a href="./home?child_id={{ $child["id"] }}" @if($child["id"] == $datas["child"]->id) class="selected-client"  @endif>
          <img src="{{ $child["icon_path"] }}">
          <span class="flex-grow-2">
            {{ $child->name }}
            @if($child->id == $datas["child"]->id)
              (選択中)
            @endif
          </span>
        </a>
      @endforeach
    @endif
    <a href="./add_child">
      <img src="../img/logo/plus.png">
      <span class="flex-grow-2">利用者を追加</span>
    </a>
  </section>
  <h1 class="flex-grow-1 font-weight-bold text-center">リンク</h1>
  <button type="button" class="settings-toggle">
    <img src="../img/logo/settings.png" />
  </button>
  <section class="settings-area">
    @if(isset($datas["child"]))
      <a href="./profile?child_id={{ $datas["child"]->id }}">
        <img src="{{ $datas["child"]->icon_path }}" />
        <span class="flex-grow-2">プロフィール</span>
      </a>
      <a class="qr">
        <img src="../img/logo/qr.png" />
        <span class="flex-grow-2">施設認証用QRコード</span>
      </a>
    @endif
    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
      <img src="../img/logo/logout.png" />
      <span class="flex-grow-2">{{ __('Logout') }}</span>
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
      @csrf
    </form>
  </section>
  <section id="qrcode" class="align-items-center justify-content-around"></section>
  <script>
    // アカウント選択、設定メニューの表示・非表示
    $(".accounts-toggle").on("click",function() {
      $(".accounts-area").slideToggle(0);
    });
    $(".settings-toggle").on("click",function() {
      $(".settings-area").slideToggle(0);
    });

    $(document).on('click',function(e) {
      if(!$(e.target).closest(".accounts-area").length && !$(e.target).closest(".accounts-toggle").length && $(".accounts-area").css("display") != "none") {
        $(".accounts-area").slideToggle(0);
      }
      if(!$(e.target).closest(".settings-area").length && !$(e.target).closest(".settings-toggle").length && $(".settings-area").css("display") != "none") {
        $(".settings-area").slideToggle(0);
      }
    });

    // QRコードの表示
    $(".qr").click(function(event){
      event.preventDefault();
      $("#qrcode").height($(window).height() - $("header").height() - $("footer").height());
      console.log($(window).height() - $("header").height() - $("footer").height());
      $("#qrcode").css("display", "flex");
    });

    $("#qrcode").click(function(event){
      $("#qrcode").css("display", "none");
    });

    @if(isset($datas["child"]))
      $("#qrcode").qrcode({width: 64, height: 64, text: "{{url('')}}/worker/approval_child?child_id={{ $datas["child"]->id }}"});
    @endif
  </script>
</header>
