<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
/* ========== �@�뭶�� ========== */
//����
Route::get('/', 'HomeController@home');
//�n�J
Route::get('login', 'MemberController@login');
//�n�J���ɦV
Route::post('member/redirect', 'MemberController@redirect');
//���U
Route::get('register', 'MemberController@register');
//Google OAuth����
Route::get('googleOAuth', 'MemberController@googleOAuth');
//�n�X
Route::get('logout', 'MemberController@logout');
//�ӤH�ɮ�
Route::get('profile/{uid?}', 'MemberController@profile');
Route::get('editProfile/{uid?}', 'MemberController@editProfile');
Route::get('gameID/{game}/{id?}', 'MemberController@gameID');
Route::get('gameID/{game?}', 'MemberController@gameID');
//�ק�K�X
Route::get('changePassword', 'MemberController@changePassword');
//���s�o�e���ҽX
Route::get('resendVerifyCode', 'MemberController@resendVerifyCode');
//��^�K�X
Route::get('findPassword', 'MemberController@findPassword');

/* ========== �޳N���� ========== */
//Hello world
Route::get('hello', 'HomeController@helloworld');
//��Ʈw�s������
Route::get('dbtest', 'CommonController@dbtest');
//�۰ʸ���
Route::get('jump', 'CommonController@jump');
//�۰ʦ^��W�@��
Route::get('jumpBack', 'CommonController@jumpBack');
//�|������
Route::get('verify/{verifyCode?}', 'MemberController@verify');
//��^�K�X���Ҩí��]�K�X
Route::get('resetPassword/{findPwdCode?}', 'MemberController@resetPassword');

/* ========== API ========== */
Route::any('api/{apiName?}', 'APIController@api');
/* ========== Ajax ========== */
Route::any('ajax/{ajaxName}/{arg?}', 'AjaxController@ajax');
Route::any('ajax/{ajaxName?}', 'AjaxController@ajax');
/* ========== �޲z��x ========== */
Route::any('admin/{method?}', 'AdminController@admin');
/* ========== �}�o�H�� ========== */
Route::any('developers/{method?}', 'DevController@developers');
/* ========== ���i ========== */
Route::any('news/{method}/{arg?}', 'NewsController@news');
Route::any('news/{method?}', 'NewsController@news');
/* ========== �C�� ========== */
Route::any('game/{method}/{arg?}', 'GameController@game');
Route::any('game/{method?}', 'GameController@game');

/* ========== ��L���� ========== */
//��v��
Route::get('slide', 'OtherController@slide');
//��ʸ˸m��������
Route::get('mobileTest', 'OtherController@mobileTest');
//���O��������
Route::get('iframeTest', 'OtherController@iframeTest');

/* ========== ���~���ɦV ========== */
/*
App::error(function(Exception $exception){
    return Redirect::to('/');
});*/