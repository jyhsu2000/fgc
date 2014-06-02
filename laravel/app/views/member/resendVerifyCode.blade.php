@extends('layouts.default')

@section('title')
    重新發送驗證郵件
@stop

@section('content')
<div class="row-fluid">
    <div class="well span6 offset3" style="min-width:340px;">
        <form id="formID" method="post" action="{{ URL::to('member/redirect') }}">
            <fieldset>
                <legend><h3>重新發送驗證郵件</h3></legend>
				<p>
					重新發送驗證碼至以下電子信箱：<br />
				</p>
				<p>
                    <div class="input-prepend">
                        <span class="input-xlarge uneditable-input">{{ member::getEmail() }}</span>
                    </div>
                </p>
                <p>
                    <input type="hidden" name="action" value="resendVerifyCode">
                    <button type="submit" class="btn btn-primary">發送驗證郵件</button>
                    <a href="
                    @if( URL::previous()!= Request::url())
                    {{ URL::previous() }}
                    @else
                    {{ URL::to('profile') }}
                    @endif
                    " class="btn">返回</a>
                </p>
            </fieldset>
        </form>
    </div>
</div>

@stop

