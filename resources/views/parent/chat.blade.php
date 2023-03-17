@extends('layouts.parent', ["datas" => $datas])

@section('styles')
<style>
    #content{
        position: absolute;
        width: 100%;
        height: calc(100% - 2 * var(--headerHeight80pxVer));
        margin:  var(--headerHeight80pxVer) 0;
    }
</style>
@endsection

@section('content')
<div id="content">
    @component('components.chat',["datas" => $datas])
    @endcomponent
</div>
@endsection
