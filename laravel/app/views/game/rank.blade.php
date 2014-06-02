@extends('layouts.default')

@section('title')
    排行榜 - 遊戲
@stop

@section('content')
<div class="row-fluid">
	<fieldset>
        <div class="offset2 span8">
            <legend><h3>排行榜</h3></legend>
            <div class="input-prepend input-append">
                <span class="add-on">遊戲：</span>
                <select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                    <option value="{{ URL::to('game/rank') }}">[請選擇遊戲]</option>
                    @foreach($gameList as $id => $item)
                        <option value="{{ URL::to('game/rank/' . $item->game) }}" @if($item->game==$gameID)selected@endif>{{ $item->gameName }}</option>
                    @endforeach
                </select>
                @if($gameID!="")
                    <a href="{{ URL::to('game/info/' . $gameID) }}" class="btn">遊戲介紹</a>
                @else
                    <button class="btn" type="button" disabled>遊戲介紹</button>
                @endif
            </div><br />
            @if($gameID!="")
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>排名</th>
                            <th>ID</th>
                            <th>遊玩次數</th>
                            <th>獲勝次數</th>
                            <th>敗北次數</th>
                            <th>勝率</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($stats)>0)
                            @foreach($stats as $id => $item)
                                <tr>
                                <td>{{ $id+1 }}</td>
                                <td><a href="{{ URL::to('gameID/'.$gameID.'/'.$item->id) }}">{{ $item->id }}</a></td>
                                <td>{{ $item->time }}</td>
                                <td>{{ $item->winTime }}</td>
                                <td>{{ $item->loseTime }}</td>
                                <td>{{ $item->rank*100 }}%</td>
                                </tr>
                            @endforeach
                        @else
                            <tr><td colspan="6" style="text-align:center;">暫時沒有資料</td></tr>
                        @endif
                    </tbody>
                </table>
            @endif
        </div>
	</fieldset>
</div>

@stop

