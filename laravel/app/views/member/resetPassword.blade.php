@extends('layouts.default')

@section('title')
    重設密碼
@stop

@section('content')
<div class="row-fluid">
    <div class="well span6 offset3" style="min-width:340px;">
        <form id="formID" method="post" action="{{ URL::to('member/redirect') }}">
            <fieldset>
                <legend><h3>重設密碼</h3></legend>
                <p>
                    <div class="input-prepend">
                        <span class="add-on"><i class="fa fa-user"></i></span>
                        <span class="input-xlarge uneditable-input">{{ $username }}</span>
                    </div>
                </p>
                <p>
                    <div class="input-prepend">
                        <span class="add-on"><i class="fa fa-lock"></i></span>
                        <input type="password" id="newPassword" name="newPassword" placeholder="請輸入新密碼..." required class="validate[required] input-xlarge">
                    </div>
                </p>
                <p>
                    <div class="input-prepend">
                        <span class="add-on"><i class="fa fa-lock"></i></span>
                        <input type="password" id="newPassword2" name="newPassword2" placeholder="請再輸入一次新密碼..." required class="validate[required,equals[newPassword]]] input-xlarge">
                    </div>
                </p>
                <p>
                    <input type="hidden" name="action" value="resetPassword">
                    <input type="hidden" name="findPwdCode" value="{{ $findPwdCode }}">
                    <button type="submit" class="btn btn-primary">重設密碼</button>
                </p>
            </fieldset>
        </form>
    </div>
</div>

@stop

