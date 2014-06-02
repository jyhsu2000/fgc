@extends('layouts.default')

@section('title')
    錯誤
@stop

@section('content')
    <div>
    @if ($msg)
    {{ $msg }}
    @else
    好像出現了什麼錯誤...
    @endif
    </div>
@stop

