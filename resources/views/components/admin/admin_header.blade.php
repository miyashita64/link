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
      border: none;
      background: none;
    }

    header > button:focus {
      outline: 5px auto #047cbc;
    }

    header > button img {
      width: 100%;
    }

    header > button > span {
      white-space: nowrap;
      position: absolute;
      left: calc(var(--headerHeight) + 3px);
      bottom: -10px;
      max-width: 80px;
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

    header > #qrcode {
      width: 100%;
      max-width: 100%;
      display: none;
      background-color: rgba(0,0,0,0.3);
    }

    header > #qrcode > canvas {
      width: 60%;
      padding: 3%;
      background-color: #adf;
    }

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
    <a href="home">
      <img src="../img/logo/home.png">
    </a>
  </button>
  <!--
  <section class="accounts-area">
    @if(isset($datas["client"]))
      @foreach($datas["clients"] as $client)
        <a href="./home?client_id={{ $client["id"] }}" @if($client["id"] == $datas["client"]["id"])  class="selected-client"  @endif>
          <img src="{{ $client["icon_path"] }}">
          <span class="flex-grow-2">
            {{ $client["name"] }}
            @if($client["id"] == $datas["client"]["id"])
              (選択中)
            @endif
          </span>
        </a>
      @endforeach
    @endif
    <a href="./add_client">
      <img src="../img/logo/plus.png">
      <span class="flex-grow-2">利用者を追加</span>
    </a>
  </section>
  -->
  <h1 class="flex-grow-1 font-weight-bold text-center">mcs.Link</h1>
  <button type="button" class="settings-toggle">
    <img src="../img/logo/settings.png" />
  </button>
  <section class="settings-area">
    <a class="qr">
      <img src="../img/logo/qr.png" />
      <span class="flex-grow-2">QRコード</span>
    </a>
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
    $("#qrcode").qrcode({width: 64, height: 64, text: "{{url('')}}/home }}"});
  </script>
</header>
