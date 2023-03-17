@extends('layouts.worker', ["datas" => $datas])

@section('styles')
<style>
    #content{
        margin: calc(var(--headerHeight80pxVer) + 20px) 5% 100px;
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
    @component('components.diarie_write', ["datas" => $datas])
    @endcomponent
</div>
@endsection
