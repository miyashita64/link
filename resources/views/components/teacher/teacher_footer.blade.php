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
    <a href="./home"><button type="button"><img src="../img/logo/list.png"></button></a>
    <a href="./analysis"><button type="button"><img src="../img/logo/children.png"></button></a>
    <a href="./document_list"><button type="button"><img src="../img/logo/document.png"></button></a>
  </section>
</footer>
