@extends('layouts.default')

@section('title')
    對戰記錄 - 遊戲
@stop

@section('content')
<div class="row-fluid">
	<fieldset>
        <div class="offset2 span8">
            <legend><h3>對戰記錄</h3></legend>
            <a href="{{ URL::to('game/record') }}" class="btn">返回對戰記錄列表</a><br /><br />
            <table class="table table-bordered">
                <tr>
                    <td style="text-align:center;" colspan="2"><a href="{{ URL::to('game/info/' . $data->game) }}">{{ $data->gameName }}</a></td>
                </tr>
                <tr>
                    <td style="text-align:center;" class="span6"><a href="{{ URL::to('gameID/'.$data->game.'/' . $data->id1) }}">{{ $data->id1 }}</a></td>
                    <td style="text-align:center;" class="span6"><a href="{{ URL::to('gameID/'.$data->game.'/' . $data->id2) }}">{{ $data->id2 }}</a></td>
                </tr>
                <tr>
                    <td colspan="2">
                    開始時間：{{ $data->startTime }}<br />
                    結束時間：{{ $data->endTime }}
                    </td>
                </tr>
            </table>
            <table class="table table-bordered table-hover" id="show">
                <tr><td style="text-align:center;">{{ nl2br($data->record) }}</td></tr>
            </table>
        </div>
	</fieldset>
</div>

@stop

