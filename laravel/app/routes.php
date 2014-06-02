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
/* ========== 一般頁面 ========== */
//首頁
Route::get('/', 'HomeController@home');
//登入
Route::get('login', 'MemberController@login');
//登入重導向
Route::post('member/redirect', 'MemberController@redirect');
//註冊
Route::get('register', 'MemberController@register');
//Google OAuth驗證
Route::get('googleOAuth', 'MemberController@googleOAuth');
//登出
Route::get('logout', 'MemberController@logout');
//個人檔案
Route::get('profile/{uid?}', 'MemberController@profile');
Route::get('editProfile/{uid?}', 'MemberController@editProfile');
Route::get('gameID/{game}/{id?}', 'MemberController@gameID');
Route::get('gameID/{game?}', 'MemberController@gameID');
//修改密碼
Route::get('changePassword', 'MemberController@changePassword');
//重新發送驗證碼
Route::get('resendVerifyCode', 'MemberController@resendVerifyCode');
//找回密碼
Route::get('findPassword', 'MemberController@findPassword');

/* ========== 技術頁面 ========== */
//Hello world
Route::get('hello', 'HomeController@helloworld');
//資料庫連結測試
Route::get('dbtest', 'CommonController@dbtest');
//自動跳轉
Route::get('jump', 'CommonController@jump');
//自動回到上一頁
Route::get('jumpBack', 'CommonController@jumpBack');
//會員驗證
Route::get('verify/{verifyCode?}', 'MemberController@verify');
//找回密碼驗證並重設密碼
Route::get('resetPassword/{findPwdCode?}', 'MemberController@resetPassword');

/* ========== API ========== */
Route::any('api/{apiName?}', 'APIController@api');
/* ========== Ajax ========== */
Route::any('ajax/{ajaxName}/{arg?}', 'AjaxController@ajax');
Route::any('ajax/{ajaxName?}', 'AjaxController@ajax');
/* ========== 管理後台 ========== */
Route::any('admin/{method?}', 'AdminController@admin');
/* ========== 開發人員 ========== */
Route::any('developers/{method?}', 'DevController@developers');
/* ========== 公告 ========== */
Route::any('news/{method}/{arg?}', 'NewsController@news');
Route::any('news/{method?}', 'NewsController@news');
/* ========== 遊戲 ========== */
Route::any('game/{method}/{arg?}', 'GameController@game');
Route::any('game/{method?}', 'GameController@game');

/* ========== 其他頁面 ========== */
//投影片
Route::get('slide', 'OtherController@slide');
//行動裝置頁面測試
Route::get('mobileTest', 'OtherController@mobileTest');
//內嵌頁面測試
Route::get('iframeTest', 'OtherController@iframeTest');

/* ========== 錯誤重導向 ========== */
/*
App::error(function(Exception $exception){
    return Redirect::to('/');
});*/