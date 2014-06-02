@extends('layouts.default')

@section('title')
    {{ $data->gameName }} - 遊戲
@stop

@section('content')
<script>
$(document).ready(function() {
    
    $('#idInGame').editable({
        type: 'text',
        pk: 1,
        url: '{{ URL::to('ajax/setID') }}?game={{ $data->game }}',
        placeholder: '角色ID',
        validate: function(value) {
            if($.trim(value) == '') {
                return '請輸入角色ID';
            }
            if(value.length > 32) {
                return '角色ID過長(不得超過32個字)';
            }
            var regex = new RegExp("^[a-zA-Z0-9]+$");
            if(regex.test(value)==false) {
                return '角色ID僅能包含英文字母與數字';
            }
        },
        display: function(value, response) {
            //render response into element
            $(this).html(response);
        }
    });
});

</script>
<div class="row-fluid">
	<fieldset>
		<legend><h3>{{ $data->gameName }}</h3></legend>
        @if($data->hide==0)
            <div class="btn-toolbar">
                <div class="btn-group">
                    <a href="{{ URL::to('game/queue/' . $data->game) }}" class="btn">等待列表</a>
                    <a href="{{ URL::to('game/rank/' . $data->game) }}" class="btn">排行榜</a>
                    <a href="{{ URL::to('game/live/' . $data->game) }}" class="btn">實況</a>
                    <a href="{{ URL::to('game/record/' . $data->game) }}" class="btn">對戰記錄</a>
                </div>
            </div>
        @else
            <div class="alert alert-error">
            目前此遊戲已設定為<b>隱藏</b><br />
            僅限此遊戲GM可見<br />
            如需修改，請點擊最下方的<b>編輯</b>
            </div>
        @endif
		<table class="table table-bordered">
			<tbody>
				<tr>
					<th style="text-align:right;" class="span2">遊戲名稱</th>
					<td>{{ $data->gameName }}</td>
				</tr>
				<tr>
					<th style="text-align:right;" class="span2">下載連結</th>
					<td><a href="{{ $data->downloadLink }}" target="_blank" title="點擊下載遊戲">{{ $data->downloadLink }}</a></td>
				</tr>
				<tr>
					<th style="text-align:right;" class="span2">簡介</th>
					<td>{{ nl2br($data->shortInfo) }}</td>
				</tr>
                @if(member::hasPerm("editGame") || member::isGM($data->game))
				<tr>
					<th style="text-align:right;" class="span2">GM</th>
                    <td>
                        @if(count($gm)>0)
                            @foreach($gm as $id => $item)
                                <a href="{{ URL::to('profile/'.$item->uid) }}">{{ $item->nickname }}</a>@if($id<count($gm)-1)、@endif
                            @endforeach
                        @else
                            <font color="gray">（暫時沒有GM）</font>
                        @endif
                    </td>
				</tr>
                @endif
				<tr>
					<th style="text-align:right;" class="span2">ID</th>
					<td>
                    @if(member::check())
                        @if(member::getGroup() == "unverified")
                            請先完成<a href="{{ URL::to('resendVerifyCode') }}">信箱驗證</a>
                        @elseif(member::getType() == "local")
                            <a href="javascript:void(0)" id="idInGame" data-name="idInGame" title="點擊編輯ID">{{ member::getID($data->game) }}</a>
                        @else
                            <font color="gray">（暫不支援外部帳號）</font>
                        @endif
                    @else
                        請先<a href="{{ URL::to('login') }}">登入</a>本地帳號
                    @endif
                    </td>
                </tr>
                @if(count($news)>0)
				<tr>
					<th style="text-align:right;" class="span2">最新消息</th>
					<td>
                        @foreach ($news as $id => $item)
                            <a href="{{ URL::to('news/read/'.$item->bid) }}">{{ $item->title }}</a> ({{ $item->date }})<br />
                        @endforeach
                        <a href="{{ URL::to('news') }}">( 所有公告 » )</a>
                    </td>
				</tr>
                @endif
				<tr>
					<td colspan="2">{{ $data->information }}</td>
				</tr>
			</tbody>
		</table>
        @if(member::hasPerm("editGame") || member::isGM($data->game))
            <a href="{{ URL::to('game/edit/'.$data->game) }}" class="btn btn-primary">編輯</a>
        @endif
        @if(member::hasPerm("editGame"))
            <a href="{{ URL::to('game/delete/'.$data->game) }}" class="btn btn-danger">刪除</a>
        @endif
		<a href="{{ URL::to('game') }}" class="btn">返回</a>
	</fieldset>
</div>

@stop

