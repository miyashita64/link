<header class="d-flex align-items-center">
  <style>
    header {
      width: 100%;
      height: var(--headerHeight80pxVer);
      position: fixed;
      top: 0;
      left: 0;
      background-color: var(--linkLightBlue);
      z-index: 10;
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
      width: calc(var(--headerHeight)*0.9);
      height: calc(var(--headerHeight)*0.9);
      margin-right: 10px;
      object-fit: cover;
    }

    header > section > a > span {
      word-break: break-all;
    }

    header > .qrcode {
      width: 100%;
      max-width: 100%;
      display: none;
      background-color: rgba(0,0,0,0.5);
    }

    @media (orientation: portrait) {
      header > .qrcode > canvas {
        width: 60%;
      }
    }

    @media (orientation: landscape) {
      header > .qrcode > canvas {
        height: 60%;
      }
    }

    header > .qrcode > canvas {
      padding: 10px;
      background-color: var(--linkLightBlue);
    }

    .facilities-area {
      left: 0;
      box-shadow: 0 0 3px 0 #047cbc;
    }

    .settings-area {
      right: 0;
      box-shadow: 0 0 3px 0 #047cbc;
    }

    .selected-facilitie{
      background-color: var(--linkLightBlue);
    }

    @media screen and (min-width: 1000px) {
      header > section {
        width: 500px;
      }
    }
  </style>
  <button type="button" class="facilities-toggle">
    <img src="../img/logo/home.png" />
    <span>
      @if(isset($datas["facilitie"]))
        {{ $datas["facilitie"]["name"] }}
      @else
        施設に登録されていません
      @endif
    </span>
  </button>
  <section class="facilities-area">
    @if(isset($datas["facilities"]))
      @foreach($datas["facilities"] as $facilitie)
        <a href="./home?facilitie_id={{ $facilitie["id"] }}" @if($facilitie["id"] == $datas["facilitie"]["id"])  class="selected-facilitie"  @endif>
          <img src="../img/logo/home.png">
          <span class="flex-grow-2">
              {{ $facilitie["name"] }}
              @if($facilitie["id"] == $datas["facilitie"]["id"])
                (選択中)
              @endif
          </span>
        </a>
      @endforeach
    @endif
    <a id="add_employ" class="qr">
      <img src="../img/logo/plus.png" />
      <span class="flex-grow-2">勤務先追加</span>
    </a>
  </section>
  <h1 class="flex-grow-1 font-weight-bold text-center">リンク</h1>
  <button type="button" class="settings-toggle">
    <img src="../img/logo/settings.png" />
  </button>
  <section class="settings-area">
    @if(isset($datas["facilitie"]))
      <a href="./profile?facilitie_id={{ $datas["facilitie"]["id"] }}">
    @else
      <a href="./profile">
    @endif
        <img src={{ Auth::user()->icon_path }} />
        <span class="flex-grow-2">プロフィール</span>
      </a>
      <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <img src="../img/logo/logout.png" />
        <span class="flex-grow-2">{{ __('Logout') }}</span>
      </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
      @csrf
    </form>
  </section>
  <section id="qrcode1" class="qrcode align-items-center justify-content-around"></section>
  <section id="qrcode2" class="qrcode align-items-center justify-content-around"></section>
  <script>
    // アカウント選択、設定メニューの表示・非表示
    $(".facilities-toggle").on("click",function() {
      $(".facilities-area").slideToggle(0);
    });

    $(".settings-toggle").on("click",function() {
      $(".settings-area").slideToggle(0);
    });

    $(document).on('click',function(e) {
      if(!$(e.target).closest(".facilities-area").length && !$(e.target).closest(".facilities-toggle").length && $(".facilities-area").css("display") != "none") {
        $(".facilities-area").slideToggle(0);
      }
      if(!$(e.target).closest(".settings-area").length && !$(e.target).closest(".settings-toggle").length && $(".settings-area").css("display") != "none") {
        $(".settings-area").slideToggle(0);
      }
    });

    // QRコードの表示
    $("#add_employ").click(function(event) {
      event.preventDefault();
      $("#qrcode1").height($(window).height() - $("header").height() - $("footer").height());
      console.log($(window).height() - $("header").height() - $("footer").height());
      $("#qrcode1").css("display", "flex");
    });

    $("#approval_parent").click(function(event) {
      event.preventDefault();
      $("#qrcode2").height($(window).height() - $("header").height() - $("footer").height());
      console.log($(window).height() - $("header").height() - $("footer").height());
      $("#qrcode2").css("display", "flex");
    });

    $(".qrcode").click(function(event) {
      $(".qrcode").css("display", "none");
    });

    $("#qrcode1").qrcode({width: 64, height: 64, text: "{{url('')}}/worker/approval_worker?worker_id={{ Auth::id() }}"});

    @if(isset($datas["client"]))
      $("#qrcode2").qrcode({width: 64, height: 64, text: "{{url('')}}/parent/approval_client?client_id={{ $datas["client"]["id"] }}"});
    @endif
  </script>
</header>
