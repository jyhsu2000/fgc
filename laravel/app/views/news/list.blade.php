@extends('layouts.default')

@section('title')
    公告
@stop

@section('content')
<div class="row-fluid">
	<fieldset>
		<legend><h3>公告</h3></legend>
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th class="span3">遊戲</th>
					<th>標題</th>
					<th class="span3">發佈時間</th>
				</tr>
			</thead>
			<tbody>
			@foreach ($data as $id => $item)
				<tr>
					<td>
                        @if($item->gameName!="")
                            <a href="{{ URL::to('game/info/'.$item->game) }}">{{ $item->gameName }}</a>
                        @else
                            <font color="red">系統公告</font>
                        @endif
                    </td>
					<td><a href="{{ URL::to('news/read/'.$item->bid) }}">{{ $item->title }}</a></td>
					<td>{{ $item->date }}</td>
				</tr>
			@endforeach
			</tbody>
		</table>
        @if(member::hasPerm("editNews") || member::isGM())
            <a href="{{ URL::to('news/new') }}" class="btn btn-primary">新增公告</a>
        @endif
		<div class="pagination pagination-centered">
			{{ $data->links() }}
		</div>
	</fieldset>
</div>

@stop

