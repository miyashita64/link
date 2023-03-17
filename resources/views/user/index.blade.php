<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>sample</title>
    </head>
    <body>
    <table>
        @php
            $keys = ["id","name","email","email_verified_at","tel","role","active","created_at","updated_at"];
        @endphp
        <tr>
        @foreach($keys as $key)
            <th>{{ $key }}</th>
        @endforeach
        </tr>
        @foreach($items as $item)
            <tr>
            @foreach($keys as $key)
                <th>{{ $item->$key }}</th>
            @endforeach
            </tr>
        @endforeach
    </table>
    </body>
</html>