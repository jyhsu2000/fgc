@extends('layouts.default')

@section('title')
    @if($email!=''){{ member::getName($email) }} - @endif個人檔案
@stop

@section('content')
@if($email=='')
<script>
$(document).ready(function() {
    
    $('#nickname').editable({
        type: 'text',
        pk: 1,
        url: '{{ URL::to('ajax/setNickname') }}',
        placeholder: '暱稱',
        validate: function(value) {
            if($.trim(value) == '') {
                return '請輸入暱稱';
            }
            if(value.length > 16) {
                return '暱稱過長(不得超過16個字)';
            }
        },
        display: function(value, response) {
            //render response into element
            $(this).html(response);
        }
    });
});
</script>
@endif
<div class="row-fluid">
    <div class="well span6 offset3" style="min-width:340px;">
        <fieldset>
            <legend><h3>@if($email!=''){{ member::getName($email) }} 的@endif個人檔案</h3></legend>
            @if($email=='')
                <a href="{{ URL::to('profile/'.$uid) }}" class="btn btn-primary">預覽</a><br /><br />
            @elseif(member::hasPerm("editProfile"))
                <a href="{{ URL::to('editProfile/'.$uid) }}" class="btn btn-primary">編輯</a><br /><br />
            @endif
            <table class="table table-bordered">
                <tbody>
                    <tr><td style="text-align:center;" colspan="2"><img src="{{ member::getImage(200,$email) }}">
                        @if($email=='')
                        <br /><a href="https://en.gravatar.com/gravatars/new/" target="_blank">[透過Gravatar更換大頭貼]</a>
                        @endif
                    </td></tr>
                    <tr><td style="text-align:right;">帳號類型：</td><td>
                        @if(member::getType($email)=="local")
                        <i class="fa fa-user fa-lg"></i> 本地帳號
                        @elseif(member::getType($email)=="facebook")
                        <i class="fa fa-facebook-square fa-lg"></i> Facebook帳號
                        @elseif(member::getType($email)=="google")
                        <i class="fa fa-google-plus-square fa-lg"></i> Google帳號
                        @endif
                    </td></tr>
                    <tr><td style="text-align:right;">暱稱：</td><td>
                        @if($email=='')
                        <a href="javascript:void(0)" id="nickname" data-name="nickname" title="點擊編輯暱稱">{{ member::getName() }}</a>
                        @else
                        {{ member::getName($email) }}
                        @endif
                    </td></tr>
                    @if($email=='' || member::hasPerm("editProfile"))
                    <tr><td style="text-align:right;">信箱：</td><td>
                        @if($email=='')
                        {{ member::getEmail() }}
                        @else
                        {{ $email }}
                        @endif
                    </td></tr>
                    @endif
                    @if(member::getType()=="local" && $email=='')<tr><td style="text-align:right;">密碼：</td><td><a href="{{ URL::to('changePassword') }}">[修改密碼]</a></td></tr>@endif
                    <tr><td style="text-align:right;">群組：</td><td>
                        @if(member::getGroup($email)=="unverified")
                        <i class="fa fa-times fa-lg"></i> 未驗證 <a href="{{ URL::to('resendVerifyCode') }}">[重新發送驗證郵件]</a>
                        @elseif(member::getGroup($email)=="user")
                        <i class="fa fa-check fa-lg"></i> 一般會員
                        @elseif(member::getGroup($email)=="admin")
                        <i class="fa fa-wrench fa-lg"></i> 管理員
                        @endif
                    </td></tr>
                </tbody>
            </table>
            @if(count($idList)>0)
            <h3>遊戲ID</h3>
            <table class="table table-bordered">
                <thead>
                    <tr><th class="span4">遊戲</th><th>ID</th></tr>
                </thead>
                <tbody>
                    @foreach($idList as $id => $item)
                        <tr>
                            <td><a href="{{ URL::to('game/info/'.$item->game) }}">{{ $item->gameName }}</a></td>
                            <td>{{ $item->id }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </fieldset>
    </div>
</div>

@stop

