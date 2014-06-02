@extends('layouts.default')

@section('title')
    {{ $data->title }} - 公告
@stop

@section('content')
<div class="row-fluid">
	<fieldset>
		<legend><h3>{{ $data->title }}</h3></legend>
		<table class="table table-bordered">
			<tbody>
				<tr>
					<th style="text-align:right;" class="span2">標題</th>
					<td>{{ $data->title }}</td>
				</tr>
				<tr>
					<th style="text-align:right;">遊戲</th>
					<td>
                        @if($data->gameName!="")
                            <a href="{{ URL::to('game/info/'.$data->game) }}">{{ $data->gameName }}</a>
                        @else
                            <font color="red">系統公告</font>
                        @endif
                    </td>
				</tr>
				<tr>
					<th style="text-align:right;">發佈時間</th>
					<td>{{ $data->date }}</td>
				</tr>
				<tr>
					<td colspan="2">{{ $data->msg }}</td>
				</tr>
			</tbody>
		</table>
        @if(member::hasPerm("editNews") || ($data->game!="" && member::isGM($data->game)))
            <a href="{{ URL::to('news/edit/'.$data->bid) }}" class="btn btn-primary">編輯</a>
            <a href="{{ URL::to('news/delete/'.$data->bid) }}" class="btn btn-danger">刪除</a>
        @endif
		<a href="{{ URL::to('news') }}" class="btn">返回</a>
	</fieldset>
</div>

@stop

