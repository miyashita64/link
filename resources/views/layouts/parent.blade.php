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
        @if(!intval($datas["is_native"]))
        @component('components.parent_header', ["datas" => $datas])
        @endcomponent
        @endif
        
        @yield('content')
        
        @if(!intval($datas["is_native"]))
        @if(isset($datas["child"]))
        @component('components.parent_footer', ["datas" => $datas])
        @endcomponent
        @endif
        @endif
        
        @yield('scripts')
    </body>
</html>