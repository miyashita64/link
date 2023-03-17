@extends('layouts.parent', ["datas" => $datas])

@section('styles')
<style>
#content{
    margin: var(--headerHeight80pxVer) 0 0;
}
</style>
@endsection

@section('content')
<div id="content">
    @component('components.calendar', ["datas" => $datas])
    @endcomponent
</div>
@endsection

@section('scripts')
<script>
    $area = $("#content");
    $area.height($area.next().offset().top-$area.offset().top);
</script>
@endsection
