<?php

class DevController extends BaseController {

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
    //developers
    public function developers($method="")
    {
        //檢查是否登入
        if(member::check() == false){
            //設定跳轉網址
            Session::put('jumpMsg',"登入方可進入此區域");
            Session::put('jumpURL',URL::to('login'));
            //導向至登入頁面
            return Redirect::to('jump');
        }
        //檢查是否有method名稱
        if(Empty($method)){
            return $this->home();
        }
        //對應功能
        switch($method){
            //投影片
            case "slide":
                return $this->slide();
                break;
            //API
            case "api":
                return $this->api();
                break;
            default:
                return Redirect::to('developers');
        }
    }
    //開發人員首頁
    public function home()
	{
        //建立View
		return View::make('developers.home');
	}
    //投影片
    public function slide()
	{
        //建立View
		return View::make('developers.slide');
	}
    //API
    public function api()
	{
        //建立View
		return View::make('developers.api');
	}
}