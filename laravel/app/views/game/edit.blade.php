@extends('layouts.default')

@section('title')
    @if($type=="edit")
        編輯遊戲
    @elseif($type=="new")
        新增遊戲
    @endif
    - 遊戲
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
var formID = function() {
    var information = $('textarea[name="information"]').html($('#summernote').code());
}
</script>
<div class="row-fluid">
    <form method="post" id="formID" action="{{ URL::to('game/redirect') }}" enctype="multipart/form-data" onsubmit="return formID()" class="form-inline">
        <fieldset>
            <legend><h3>
                @if($type=="edit")
                    編輯遊戲
                @elseif($type=="new")
                    新增遊戲
                @endif
            </h3></legend>
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th style="text-align:right;" class="span3">遊戲ID</th>
                        <td>
                        @if($type=="edit")
                            {{ $data->game }}
                        @else
                            <input type="text" id="game" name="game" placeholder="請輸入遊戲ID..." class="span12 validate[custom[onlyLetterNumber]]" required autofocus>
                        @endif
                        </td>
                    </tr>
                    <tr>
                        <th style="text-align:right;" class="span3">遊戲名稱</th>
                        <td><input type="text" id="gameName" name="gameName" placeholder="請輸入遊戲名稱..." class="span12" value="@if($type=="edit"){{ $data->gameName }}@endif" required autofocus></td>
                    </tr>
                    <tr>
                        <th style="text-align:right;" class="span3">下載連結</th>
                        <td><input type="url" id="downloadLink" name="downloadLink" placeholder="請貼上遊戲下載網址..." class="span12 validate[custom[url]]" value="@if($type=="edit"){{ $data->downloadLink }}@endif"></td>
                    </tr>
                    <tr>
                        <th style="text-align:right;" class="span3">簡介<br />(3行以內)</th>
                        <td><textarea class="span12" id="shortInfo" name="shortInfo" rows="3" placeholder="請輸入遊戲簡介，將顯示於遊戲清單，限制3行以內">@if($type=="edit"){{ $data->shortInfo }}@endif</textarea></td>
                    </tr>
                    <tr>
                        <th style="text-align:right;" class="span3">屬性</th>
                        <td>
                        <label class="checkbox"><input type="checkbox" name="hide" value="1" @if($type=="edit" && $data->hide==1)checked@endif>隱藏遊戲</label><font color="gray">（隱藏後，限GM可見）</font>
                        </td>
                    </tr>
                    @if(member::hasPerm("editGame"))
                    <tr>
                        <th style="text-align:right;" class="span3">GM列表<br />(每行一個帳號)</th>
                        <td><textarea class="span12 validate[custom[emailMultiLine]]" id="gm" name="gm" rows="5" placeholder="請填寫GM的username，每行一個">@if($type=="edit"){{ $gm }}@endif</textarea></td>
                    </tr>
                    @endif
                    <tr>
                        <td colspan="2">
                        <textarea class="span12" id="summernote" name="information" rows="20">@if($type=="edit"){{ $data->information }}@endif</textarea>
                        </td>
                    </tr>
                </tbody>
            </table>
            @if($type=="edit")
                <input type="hidden" name="game" value="{{ $data->game }}">
            @endif
            <input type="hidden" name="action" value="{{ $type }}">
            <button type="submit" class="btn btn-primary">完成</button>
            @if($type=="edit")
                <a href="{{ URL::to('game/info/'.$data->game) }}" class="btn">返回</a>
            @elseif($type=="new")
                <a href="{{ URL::to('game') }}" class="btn">返回</a>
            @endif
        </fieldset>
    </form>
</div>

@stop

