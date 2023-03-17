@extends('layouts.teacher', ["datas" => $datas])

@section('styles')
<style>
#content{
    margin: calc(var(--headerHeight80pxVer) + 20px) 3% 100px;
}
</style>
@endsection

@section('content')
<div id="content">
    @php
        $list_datas = [
            [
                "url" => "./student_list",
                "title" => "学生",
                "img_path" => "../img/logo/children.png",
            ],[
                "url" => "./teacher_list",
                "title" => "教員",
                "img_path" => "../img/logo/people.png",
            ],[
                "url" => "./classroom_list",
                "title" => "クラス",
                "img_path" => "../img/logo/children.png",
            ]
        ];
    @endphp
    @component('components.list', ["list_datas" => $list_datas])
    @endcomponent
</div>
@endsection
