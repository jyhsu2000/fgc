@extends('layouts.default')

@section('title')
    實況 - 遊戲
@stop

@section('content')
<script type="text/javascript">
function autoRenew(){
    var now = new Date(); 
    var datetime = now.fullTime();
    $('#lastUpdate').html(datetime);
    $('#show').load('{{ $ajaxURL }}');
}
$(document).ready(function() {
    autoRenew();
    setInterval(autoRenew, 10000);
});
</script>
<div class="row-fluid">
	<fieldset>
        <div class="offset2 span8">
            <legend><h3>實況</h3></legend>
            <a href="{{ URL::to('game/live') }}" class="btn">返回實況列表</a><br /><br />
            <table class="table table-bordered">
                <tr>
                    <td style="text-align:center;" colspan="2"><a href="{{ URL::to('game/info/' . $data->game) }}">{{ $data->gameName }}</a></td>
                </tr>
                <tr>
                    <td style="text-align:center;" class="span6"><a href="{{ URL::to('gameID/'.$data->game.'/' . $data->id1) }}">{{ $data->id1 }}</a></td>
                    <td style="text-align:center;" class="span6"><a href="{{ URL::to('gameID/'.$data->game.'/' . $data->id2) }}">{{ $data->id2 }}</a></td>
                </tr>
                <tr>
                    <td colspan="2">開始時間：{{ $data->startTime }}</td>
                </tr>
            </table>
            最後刷新時間：<span id="lastUpdate"></span>
            <table class="table table-bordered table-hover" id="show">
                <tr><td style="text-align:center;">讀取中...</td></tr>
            </table>
        </div>
	</fieldset>
</div>

@stop

