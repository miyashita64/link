@extends('layouts.link_admin', ["datas" => $datas])

@section('styles')
<style>
    #content{
        margin: var(--headerHeight80pxVer) 0;
    }
</style>
@endsection

@section('content')
<div id="content">
    @component('components.chat',["datas" => $datas])
    @endcomponent
</div>
@endsection
