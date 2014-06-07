@extends('layouts.default')

@section('title')
    實況 - 遊戲
@stop

@section('content')
<div class="row-fluid">
	<fieldset>
        <div class="offset2 span8">
            <legend><h3>實況</h3></legend>
            <div class="input-prepend input-append">
                <span class="add-on">遊戲：</span>
                <select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                    <option value="{{ URL::to('game/live') }}">[所有遊戲]</option>
                    @foreach($gameList as $id => $item)
                        <option value="{{ URL::to('game/live/' . $item->game) }}" @if($item->game==$gameID)selected@endif>{{ $item->gameName }}</option>
                    @endforeach
                </select>
                @if($gameID!="")
                    <a href="{{ URL::to('game/info/' . $gameID) }}" class="btn">遊戲介紹</a>
                @else
                    <button class="btn" type="button" disabled>遊戲介紹</button>
                @endif
            </div><br />
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        @if($gameID=="")<th class="span3">遊戲</th>@endif
                        <th>玩家1</th>
                        <th>玩家2</th>
                        <th>開始時間</th>
                        <th>動作</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($roomList)>0)
                        @foreach($roomList as $id => $item)
                            <tr>
                                @if($gameID=="")<td><a href="{{ URL::to('game/info/'.$item->game) }}">{{ $item->gameName }}</a></td>@endif
                                <td><a href="{{ URL::to('gameID/'.$item->game.'/'.$item->id1) }}">{{ $item->id1 }}</a></td>
                                <td><a href="{{ URL::to('gameID/'.$item->game.'/'.$item->id2) }}">{{ $item->id2 }}</a></td>
                                <td>{{ $item->startTime }}</td>
                                <td style="text-align:center;"><a href="{{ URL::to('game/live').'/?rid='.$item->rid }}" class="btn btn-primary">進入</a></td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            @if($gameID=="")
                            <td colspan="5" style="text-align:center;">
                            @else
                            <td colspan="4" style="text-align:center;">
                            @endif
                                目前沒有進行中的遊戲
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="clearfix"></div>
        <div class="pagination pagination-centered">
            {{ $roomList->links() }}
        </div>
	</fieldset>
</div>

@stop

