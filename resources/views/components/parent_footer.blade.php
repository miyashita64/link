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
    <a href="./home?child_id={{ $datas["child"]->id }}">
      <button type="button">
        <img src="../img/logo/book.png">
      </button>
    </a>
    <a href="./chat_list?child_id={{ $datas["child"]->id }}">
      <button>
        <img src="../img/logo/chat.png">
      </button>
    </a>
    <a href="./calendar?child_id={{ $datas["child"]->id }}">
      <button type="button">
        <img src="../img/logo/calendar.png">
      </button>
    </a>
  </section>
</footer>
