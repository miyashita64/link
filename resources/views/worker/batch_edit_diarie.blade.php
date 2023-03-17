@extends('layouts.worker', ["datas" => $datas])

@section('styles')
<style>
#content{
    margin: var(--headerHeight80pxVer) 5% 100px;
}
</style>
@endsection

@section('content')
<div id="content">
    @component('components.batch_edit_diarie', ["datas" => $datas])
    @endcomponent
</div>
@endsection
