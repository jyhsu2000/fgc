<?php

class CommonController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function dbtest()
	{
        //資料庫查詢
        $results = DB::select('select * from `test`');
        //將變數指派給View
        View::share('results', $results);
        //建立View
		return View::make('dbtest');
	}
    public function error()
	{
        //建立View
		return View::make('error');
	}
    public function jump()
	{
        //自動跳轉網址
        if(Session::has('jumpURL')){
            $jumpURL = Session::get('jumpURL');
        }else{
            $jumpURL = URL::to('/');
        }
        View::share('jumpURL', $jumpURL);
        //自動跳轉訊息
        if(Session::has('jumpMsg')){
            $jumpMsg = Session::get('jumpMsg');
        }else{
            $jumpMsg = "";
        }
        View::share('jumpMsg', $jumpMsg);
        //清除Session
        Session::forget('jumpURL');
        Session::forget('jumpMsg');
        //建立View
		return View::make('jump');
	}
    public function jumpBack()
	{
        //自動跳轉訊息
        if(Session::has('jumpMsg')){
            $jumpMsg = Session::get('jumpMsg');
        }else{
            $jumpMsg = "";
        }
        View::share('jumpMsg', $jumpMsg);
        //清除Session
        Session::forget('jumpMsg');
        //建立View
		return View::make('jumpBack');
	}

}