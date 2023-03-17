<footer>
  <style>
    footer {
      width: 100%;
      height: var(--headerHeight80pxVer);
      position: fixed;
      bottom: 0;
      left: 0;
      font-size: 0;
      background-color: var(--linkLightBlue);
      z-index: 2;
    }

    footer button {
      width: calc(100% / 3);
      header: var(--headerHeight);
      text-align: center;
      line-height: 80px;
      background-color: var(--linkLightBlue);
      border: none;
    }

    footer button img {
      height: var(--headerHeight);
    }

    footer button:focus {
      outline: none;
    }
  </style>
  <section>
    @if(isset($datas["facilitie"]))
      <a href="./home?facilitie_id={{ $datas["facilitie"]["id"] }}">
        <button type="button">
          <img src="../img/logo/children.png">
        </button>
      </a>
      @php //@if($datas["employments"]->find($datas["facilitie"]["id"])["parmit"] <= 5) @endphp
      <a href="./chat_list?facilitie_id={{ $datas["facilitie"]["id"] }}">
        <button>
          <img src="../img/logo/chat.png">
        </button>
      </a>
      @php
      /*
    @else
      <a href="./chat?facilitie_id={{ $datas["facilitie"]["id"] }}">
        <button>
          <img src="../img/logo/chat.png">
        </button>
      </a>
    @endif
      */
      @endphp
      <a href="./report_list?facilitie_id={{ $datas["facilitie"]["id"] }}">
        <button type="button">
          <img src="../img/logo/book.png">
        </button>
      </a>
    @endif
  </section>
</footer>
