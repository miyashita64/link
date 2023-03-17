@extends('layouts.worker', ["datas" => $datas])

@section('styles')
<style>
#content{
    margin: var(--headerHeight80pxVer) 0 120px;
}
</style>
@endsection

@section('content')
<div id="content">
    @component('components.input_worker', ["datas" => $datas])
    @endcomponent
</div>
@endsection
