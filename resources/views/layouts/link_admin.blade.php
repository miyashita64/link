<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $datas["title"] }}</title>
        <meta name="description" content="リンクは障がい児童福祉施設向けの業務サポートアプリです">
        <link rel="apple-touch-icon" href="{{ asset('img/link_logo.jpg') }}" />
        <link rel="stylesheet" href="{{ asset('css/destyle.css') }}">
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <link rel="stylesheet" href="{{ asset('css/link.css') }}">
        <link rel="stylesheet" href="{{ asset('css/jquery-ui.min.css') }}">
        @yield('styles')
        <script src="{{ asset('js/jquery-3.5.1.min.js') }}"></script>
        <script src="{{ asset('js/jquery.qrcode.min.js') }}"></script>
        <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
        <script src="{{ asset('js/datepicker-ja.js') }}"></script>
    </head>
    <body>
        @component('components.admin.admin_header', ["datas" => $datas])
        @endcomponent

        @yield('content')
        
        @yield('scripts')
    </body>
</html>