@extends('layouts.default')

@section('title')
    會員管理 - 管理後台
@stop

@section('content')
<div class="row-fluid">
	<fieldset>
		<legend><h3>會員管理</h3></legend>
        <div class="pagination pagination-centered">
			{{ $data->links() }}
		</div>
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th>編號(uid)</th>
					<th width="44px"></th>
					<th>帳號(username)</th>
					<th>暱稱(nickname)</th>
					<th>登入類型(logintype)</th>
					<th>群組(group)</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($data as $id => $item)
					<tr style="height: 60px">
						<td>{{ $item->uid }}</td>
						<td><img src={{ member::getImage(40,$item->username) }}/></td>
						<td>{{ $item->username }}</td>
						<td><a href="{{ URL::to('profile/'.$item->uid) }}" title="點擊查看個人資料">{{ $item->nickname }}</a></td>
						<td>
							@if($item->loginType=="local")
							<i class="fa fa-user fa-lg"></i> 本地帳號
							@elseif($item->loginType=="facebook")
							<i class="fa fa-facebook-square fa-lg"></i> Facebook
							@elseif($item->loginType=="google")
							<i class="fa fa-google-plus-square fa-lg"></i> Google
							@endif
						</td>
						<td>
							@if($item->group=="unverified")
							<i class="fa fa-times fa-lg"></i> 未驗證
							@elseif($item->group=="user")
							<i class="fa fa-check fa-lg"></i> 一般會員
							@elseif($item->group=="admin")
							<i class="fa fa-wrench fa-lg"></i> 管理員
							@endif
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
        <div class="pagination pagination-centered">
			{{ $data->links() }}
		</div>
	</fieldset>
</div>

@stop

