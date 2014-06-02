<?php

class HomeController extends BaseController {

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

	public function helloworld()
	{
        return View::make('helloworld');
	}
    
    public function home()
	{
		//讀取公告清單
		$amount = 5;
		$data = DB::table('bulletin')->orderBy('bid', 'desc')->leftJoin('game', 'bulletin.game', '=', 'game.game')->whereNull('bulletin.game')->orWhere('game.hide',0)->take($amount)->get();
        View::share('data',$data);
		//建立View
		return View::make('home');
	}

}