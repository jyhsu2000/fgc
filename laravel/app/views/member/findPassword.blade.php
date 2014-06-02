@extends('layouts.default')

@section('title')
    找回密碼
@stop

@section('content')
<div class="row-fluid">
    <div class="well span6 offset3" style="min-width:340px;">
        <form id="formID" method="post" action="{{ URL::to('member/redirect') }}">
            <fieldset>
                <legend><h3>找回密碼</h3></legend>
				<p>
					請輸入註冊時填寫的電子信箱：<br />
				</p>
				<p>
                    <div class="input-prepend">
                        <span class="add-on"><i class="fa fa-user"></i></span>
                        <input type="text" id="username" name="username" placeholder="請輸入信箱..." required autofocus class="validate[required,custom[email]] input-xlarge">
                    </div>
                </p>
                <p>
                    <input type="hidden" name="action" value="findPassword">
                    <button type="submit" class="btn btn-primary">提交</button>
                    <a href="{{ URL::previous() }}" class="btn">返回</a>
                </p>
            </fieldset>
        </form>
    </div>
</div>

@stop

