@extends('layouts.default')

@section('title')
    @if($type=="edit")
        編輯公告
    @elseif($type=="new")
        新增公告
    @endif
    - 公告
@stop

@section('content')
<script type="text/javascript">
$(document).ready(function() {
    $('#summernote').summernote({
        height: 400,   //set editable area's height
        lang: 'zh-TW', // default: 'en-US'
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            //['fontname', ['fontname']],
            //['fontsize', ['fontsize']], Still buggy
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            //['height', ['height']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            //['view', ['fullscreen', 'codeview']],
            ['view', ['codeview']],
            //['help', ['help']]
        ],
    });
});
var postForm = function() {
    var msg = $('textarea[name="msg"]').html($('#summernote').code());
}
</script>
<div class="row-fluid">
    <form method="post" id="postForm" action="{{ URL::to('news/redirect') }}" enctype="multipart/form-data" onsubmit="return postForm()" class="form-inline">
        <fieldset>
            <legend><h3>
                @if($type=="edit")
                    編輯公告
                @elseif($type=="new")
                    新增公告
                @endif
            </h3></legend>
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th style="text-align:right;" class="span2">標題</th>
                        <td><input type="text" id="title" name="title" placeholder="請輸入標題..." class="span12" value="@if($type=="edit"){{ $data->title }}@endif" required autofocus></td>
                    </tr>
                    <tr>
                        <th style="text-align:right;">遊戲</th>
                        <td>
                            <select name="game">
                                @if(member::hasPerm("editNews"))<option value="">[系統公告]</option>@endif
                                @foreach ($game as $id => $item)
                                    <option value="{{ $item->game }}" @if($type=="edit" && $data->game==$item->game)selected@endif>{{ $item->gameName }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    @if($type=="edit")
                        <tr>
                            <th style="text-align:right;">發佈時間</th>
                            <td>{{ $data->date }}</td>
                        </tr>
                    @endif
                    <tr>
                        <td colspan="2">
                        <textarea class="span12" id="summernote" name="msg" rows="20">@if($type=="edit"){{ $data->msg }}@endif</textarea>
                        </td>
                    </tr>
                </tbody>
            </table>
            @if($type=="edit")
                <input type="hidden" name="bid" value="{{ $data->bid }}">
            @endif
            <input type="hidden" name="action" value="{{ $type }}">
            <button type="submit" class="btn btn-primary">完成</button>
            @if($type=="edit")
                <a href="{{ URL::to('news/read/'.$data->bid) }}" class="btn">返回</a>
            @elseif($type=="new")
                <a href="{{ URL::to('news') }}" class="btn">返回</a>
            @endif
        </fieldset>
    </form>
</div>

@stop

