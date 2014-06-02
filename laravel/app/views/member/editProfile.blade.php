@extends('layouts.default')

@section('title')
    {{ member::getName($email) }} - 編輯個人檔案
@stop

@section('content')

<div class="row-fluid">
    <div class="well span6 offset3" style="min-width:340px;">
        <fieldset>
            <legend><h3>編輯 {{ member::getName($email) }} 的個人檔案</h3></legend>
            <form id="formID" method="post" action="{{ URL::to('member/redirect') }}" class="form-inline">
                <table class="table table-bordered">
                    <tbody>
                        <tr><td style="text-align:center;" colspan="2"><img src="{{ member::getImage(200,$email) }}">
                        </td></tr>
                        <tr><td style="text-align:right;">帳號類型：</td><td>
                            <select name="loginType">
                                <option value="local" @if(member::getType($email)=="local")selected@endif>本地帳號</option>
                                <option value="facebook" @if(member::getType($email)=="facebook")selected@endif>Facebook帳號</option>
                                <option value="google" @if(member::getType($email)=="google")selected@endif>Google帳號</option>
                            </select>
                        </td></tr>
                        <tr><td style="text-align:right;">暱稱：</td><td>
                            <input type="text" id="nickname" name="nickname" placeholder="請輸入暱稱..." required class="validate[minSize[1],maxSize[16]]" value="{{ member::getName($email) }}">
                        </td></tr>
                        <tr><td style="text-align:right;">信箱：</td><td>{{ $email }}</td></tr>
                        <tr><td style="text-align:right;">密碼：</td><td>
                            <input type="text" id="password" name="password" placeholder="若不修改請留白">
                        </td></tr>
                        <tr><td style="text-align:right;">群組：</td><td>
                            <select name="group">
                            @foreach ($group as $id => $item)
                                @if($item->group != "guest")
                                    <option value="{{ $item->group }}" @if(member::getGroup($email)==$item->group)selected@endif>{{ $item->groupName }}</option>
                                @endif
                            @endforeach
                            </select>
                        </td></tr>
                        <tr><td style="text-align:center;" colspan="2">
                            <input type="hidden" name="action" value="editProfile">
                            <input type="hidden" name="uid" value="{{ $uid }}">
                            <button type="submit" class="btn btn-primary">完成</button>
                            <a href="{{ URL::to('profile/'.$uid) }}" class="btn">返回</a>
                        </td></tr>
                    </tbody>
                </table>
            </form>
        </fieldset>
    </div>
</div>

@stop

