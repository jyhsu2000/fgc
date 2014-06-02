@extends('layouts.default')

@section('title')
    等待列表 - 遊戲
@stop

@section('content')
<script type="text/javascript">
function autoRenew(){
    var now = new Date(); 
    var datetime = now.fullTime();
    $('#lastUpdate').html(datetime);
    $('#queueList').load('{{ $ajaxURL }}');
}
$(document).ready(function() {
    autoRenew();
    setInterval(autoRenew, 10000);
});
</script>
<div class="row-fluid">
	<fieldset>
        <div class="offset2 span8">
            <legend><h3>等待列表</h3></legend>
            <div class="input-prepend input-append">
                <span class="add-on">遊戲：</span>
                <select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                    <option value="{{ URL::to('game/queue') }}">[所有遊戲]</option>
                    @foreach($gameList as $id => $item)
                        <option value="{{ URL::to('game/queue/' . $item->game) }}" @if($item->game==$gameID)selected@endif>{{ $item->gameName }}</option>
                    @endforeach
                </select>
                @if($gameID!="")
                    <a href="{{ URL::to('game/info/' . $gameID) }}" class="btn">遊戲介紹</a>
                @else
                    <button class="btn" type="button" disabled>遊戲介紹</button>
                @endif
            </div><br />            
            最後刷新時間：<span id="lastUpdate"></span>
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>遊戲</th>
                        <th>ID</th>
                        <th>加入時間</th>
                    </tr>
                </thead>
                <tbody id="queueList">
                    <tr><td colspan="3" style="text-align:center;">等待列表載入中...</td></tr>
                </tbody>
            </table>
        </div>
	</fieldset>
</div>

@stop

