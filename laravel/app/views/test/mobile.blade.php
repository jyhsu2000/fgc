@extends('layouts.mobile')

@section('title')
    登入
@stop

@section('content')
<div class="row-fluid">
    <div class="well" style="min-width:340px;">
        <form method="post" action="{{ URL::to('member/redirect') }}">
            <fieldset>
                <legend><h3>登入</h3></legend>
                <p>
                    <div class="input-prepend">
                        <span class="add-on"><i class="fa fa-user"></i></span>
                        <input type="text" id="username" name="username" placeholder="請輸入信箱..." autofocus>
                    </div>
                </p>
                <p>
                    <div class="input-prepend">
                        <span class="add-on"><i class="fa fa-lock"></i></span>
                        <input type="password" id="password" name="password" placeholder="請輸入密碼...">
                    </div>
                </p>
                <p>
                    <label class="checkbox inline">
                        <input type="checkbox" name="remember"> 記住我
                    </label>
                </p>
                <p>
                    <input type="hidden" name="action" value="mobileLogin">
                    <button type="submit" class="btn btn-primary btn-block btn-large">登入</button>
                </p>
            </fieldset>
        </form>
    </div>
</div>

@stop

