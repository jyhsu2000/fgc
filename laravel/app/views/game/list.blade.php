@extends('layouts.default')

@section('title')
    遊戲
@stop

@section('content')
<div class="row-fluid">
	<fieldset>
        <div class="offset2 span8">
            <legend><h3>遊戲</h3></legend>
            <table class="table table-bordered table-hover">
                <tbody>
                @foreach ($data as $id => $item)
                    @if($item->hide!=1 || member::isGM($item->game))
                        @if($item->hide==1)
                        <tr class="error">
                        @else
                        <tr>
                        @endif
                            <td>
                                <h3>@if($item->hide==1)<a href="javascript:void(0)" title="此遊戲目前設定為隱藏"><i class="fa fa-lock"></i></a> @endif<a href="{{ URL::to('game/info/' . $item->game ) }}">{{ $item->gameName }}</a></h3>
                                @if(count($gm[$item->game])>0 && (member::hasPerm("editGame") || member::isGM($item->game)))
                                    <span class="label label-important">GM</span>
                                    @foreach($gm[$item->game] as $gmId => $gmItem)
                                        <a href="{{ URL::to('profile/'.$gmItem->uid) }}">{{ $gmItem->nickname }}</a>@if($gmId<count($gm[$item->game])-1)、@endif
                                    @endforeach
                                    <br />
                                @endif
                                @if(!Empty($news[$item->game]))
                                    <span class="label label-important">最新消息</span>
                                    <a href="{{ URL::to('news/read/'.$news[$item->game]->bid) }}">{{ $news[$item->game]->title }}</a> ({{ $news[$item->game]->date }})
                                    <br />
                                @endif
                                <font color="gray">{{ nl2br($item->shortInfo) }}</font>
                                @if(member::getID($item->game) != null)
                                <br /><span class="label label-info">ID</span> {{ member::getID($item->game) }}
                                @endif
                            </td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
            @if(member::hasPerm("editGame"))
                <a href="{{ URL::to('game/new') }}" class="btn btn-primary">新增遊戲</a>
            @endif
        </div>
	</fieldset>
</div>

@stop

