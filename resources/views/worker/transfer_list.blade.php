@extends('layouts.worker', ["datas" => $datas])

@section('styles')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    #content{
        width: 94%;
        margin: calc(var(--headerHeight80pxVer) + 20px) 3% 100px;
    }

    @media screen and (min-width: 1000px) {
        #content {
            width: 70%;
            margin-left: 15%;
        }
    }
</style>
@endsection

@section('content')
<div id="content">
    @component('components.transfer_list', ["datas" => $datas])
    @endcomponent
</div>
@endsection
