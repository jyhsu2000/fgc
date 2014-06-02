@extends('layouts.default')

@section('title')
    註冊
@stop

@section('content')
<div class="row-fluid">
    <div class="well span6 offset3" style="min-width:340px;">
        <form id="formID" method="post" action="{{ URL::to('member/redirect') }}">
            <fieldset>
                <legend><h3>註冊</h3></legend>
                <p>
                    <div class="input-prepend">
                        <span class="add-on"><i class="fa fa-user"></i></span>
                        <input type="text" id="username" name="username" placeholder="請輸入信箱..." required autofocus class="validate[required,custom[email]] input-xlarge">
                    </div>
                </p>
                <p>
                    <div class="input-prepend">
                        <span class="add-on"><i class="fa fa-lock"></i></span>
                        <input type="password" id="password" name="password" placeholder="請輸入密碼..." required class="validate[required] input-xlarge">
                    </div>
                </p>
                <p>
                    <div class="input-prepend">
                        <span class="add-on"><i class="fa fa-lock"></i></span>
                        <input type="password" id="password2" name="password2" placeholder="請再輸入一次密碼..." required class="validate[required,equals[password]]] input-xlarge">
                    </div>
                </p>
                <p>
                    <input type="hidden" name="action" value="register">
                    <button type="submit" class="btn btn-primary">註冊</button>
                </p>
            </fieldset>
        </form>
    </div>
</div>

@stop

