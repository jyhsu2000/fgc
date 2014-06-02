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
        //��Ʈw�d��
        $results = DB::select('select * from `test`');
        //�N�ܼƫ�����View
        View::share('results', $results);
        //�إ�View
		return View::make('dbtest');
	}
    public function error()
	{
        //�إ�View
		return View::make('error');
	}
    public function jump()
	{
        //�۰ʸ�����}
        if(Session::has('jumpURL')){
            $jumpURL = Session::get('jumpURL');
        }else{
            $jumpURL = URL::to('/');
        }
        View::share('jumpURL', $jumpURL);
        //�۰ʸ���T��
        if(Session::has('jumpMsg')){
            $jumpMsg = Session::get('jumpMsg');
        }else{
            $jumpMsg = "";
        }
        View::share('jumpMsg', $jumpMsg);
        //�M��Session
        Session::forget('jumpURL');
        Session::forget('jumpMsg');
        //�إ�View
		return View::make('jump');
	}
    public function jumpBack()
	{
        //�۰ʸ���T��
        if(Session::has('jumpMsg')){
            $jumpMsg = Session::get('jumpMsg');
        }else{
            $jumpMsg = "";
        }
        View::share('jumpMsg', $jumpMsg);
        //�M��Session
        Session::forget('jumpMsg');
        //�إ�View
		return View::make('jumpBack');
	}

}