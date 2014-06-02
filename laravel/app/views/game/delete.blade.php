@extends('layouts.default')

@section('title')
    刪除遊戲
@stop

@section('content')
<div class="row-fluid">
    <div class="well span6 offset3" style="min-width:340px;">
        <form id="formID" method="post" action="{{ URL::to('game/redirect') }}">
            <fieldset>
                <legend><h3>刪除遊戲</h3></legend>
				<p>
					即將刪除以下遊戲：
				</p>
				<p>
                    {{ $data->gameName }}
                </p>
                <p>
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="game" value="{{ $data->game }}">
                    <button type="submit" class="btn btn-danger">刪除</button>
                    <a href="{{ URL::to('game/info/'.$data->game) }}" class="btn">取消</a>
                </p>
            </fieldset>
        </form>
    </div>
</div>

@stop

