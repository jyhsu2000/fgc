@extends('layouts.default')

@section('title')
    登入
@stop

@section('content')
<div class="row-fluid">
    @if(Config::get('config.allowOAuth'))
    <div class="well span4 offset3" style="min-width:340px;">
    @else
    <div class="well span6 offset3" style="min-width:340px;">
    @endif
        <form method="post" action="{{ URL::to('member/redirect') }}">
            <fieldset>
                <legend><h3>登入</h3></legend>
                <p>
                    <div class="input-prepend">
                        <span class="add-on"><i class="fa fa-user"></i></span>
                        <input type="text" id="username" name="username" placeholder="請輸入信箱..." required autofocus>
                    </div>
                </p>
                <p>
                    <div class="input-prepend">
                        <span class="add-on"><i class="fa fa-lock"></i></span>
                        <input type="password" id="password" name="password" placeholder="請輸入密碼..." required>
                    </div>
                </p>
                <p>
                    <label class="checkbox inline">
                        <input type="checkbox" name="remember"> 記住我
                    </label>
                </p>
                <p>
                    <input type="hidden" name="action" value="login">
                    <button type="submit" class="btn btn-primary">登入</button>
                </p>
                <p>
                    <a href="{{ URL::to('findPassword') }}">忘記密碼？</a>
                </p>
            </fieldset>
        </form>
    </div>
    @if(Config::get('config.allowOAuth'))
    <div class="well span3" style="min-width:180px;">
        <fieldset>
            <legend><h4>其他登入方式</h3></legend>
            <p><a class="btn btn-block" href="{{ $fb_login_url }}"><i class="fa fa-facebook-square fa-2x"></i> Facebook 帳號</a></p>
            <p><a class="btn btn-block" href="{{ $GoogleAuthUrl }}"><i class="fa fa-google-plus-square fa-2x"></i> Google 帳號</a></p>
            <p><font color="gray">（僅能登入網站，暫不支援遊戲端）</font></p>
        </fieldset>
    </div>
    @endif
</div>

@stop

