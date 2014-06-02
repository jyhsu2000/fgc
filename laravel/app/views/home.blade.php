@extends('layouts.default')

@section('title')
    首頁
@stop

@section('content')
<div class="row-fluid">
	<div class="row-fluid">
		<div class="offset2 span8">
            <div class="alert alert-info text-left">
                <div class="text-center"><h2>屯門遊樂局</h2></div>
                <h3>屯門遊樂局是什麼？能吃嗎？</h3>
                <code>屯門遊樂局</code>是一個網路遊戲平台<br />
                由一群熱血的逢甲學生共同創立<br />
                提供了各式各樣的<code>免費小遊戲</code><br />
                每個遊戲都有詳細的介紹及說明，且全部提供<code>自由下載</code>
                <h3>如何開始？</h3>
                <ol>
                    <li><a href="{{ URL::to('register') }}">註冊帳號</a>並完成信箱驗證
                    <li><a href="{{ URL::to('login') }}">登入</a>網站
                    <li>進入<a href="{{ URL::to('game') }}">遊戲</a>頁面，選擇想玩的遊戲
                    <li>建立角色ID
                    <li>下載遊戲
                    <li>啟動遊戲並登入帳號
                    <li>沈浸於遊戲帶給你的一切
                </ol>
            </div>
            @if(count($data)>0)
			<table class="table table-bordered table-hover">
				<thead>
					<tr>
						<th>最新消息</th>
					</tr>
				</thead>
				<tbody>
                @foreach ($data as $id => $item)
                    <tr>
                        <td>
                            @if($item->gameName!="")
                                [<a href="{{ URL::to('game/info/'.$item->game) }}">{{ $item->gameName }}</a>]
                            @else
                                <font color="red">[系統公告]</font>
                            @endif
                            <a href="{{ URL::to('news/read/'.$item->bid) }}">{{ $item->title }}</a> ({{ $item->date }})
                        </td>
                    </tr>
                @endforeach
				</tbody>
				<tfoot>
					<tr>
						<td style="text-align:right;"><a href="{{ URL::to('news') }}">更多 »</a></td>
					</tr>
				</tfoot>
			</table>
            @endif
		<div>
	</div>
</div>

@stop