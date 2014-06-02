@extends('layouts.default')

@section('title')
    自動跳轉
@stop

@section('content')
    <script>
    setTimeout("location.href='{{ URL::previous() }}'",3000);
    </script>
    <div class="row-fluid">
        <div class="well span6 offset3" style="min-width:340px;">
            {{ $jumpMsg }}<br />
            <br />
            3秒後自動回到上一頁...<br />
            <a href="{{ URL::previous() }}">[若瀏覽器沒有回到上一頁，請點擊此處]</a>
        </div>
    </div>
@stop

