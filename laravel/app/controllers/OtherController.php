<?php

class OtherController extends BaseController {

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

	public function slide()
	{
        //建立View
        return View::make('slide');
	}
    
    public function mobileTest()
	{
        //建立View
        return View::make('test.mobile');
	}
    public function iframeTest()
	{
        //建立View
        return View::make('test.iframe');
	}
}