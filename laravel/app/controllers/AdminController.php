<?php

class AdminController extends BaseController {

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
    //admin
    public function admin($method="")
    {
        //檢查是否有訪問後台權限
        if(!member::hasPerm("permAdmincp")){
            return Redirect::to('/');
        }
        
        //檢查是否有method名稱
        if(Empty($method)){
            return $this->home();
        }
        //對應功能
        switch($method){
            //會員管理
            case "member":
                return $this->member();
                break;
            default:
                return Redirect::to('admin');
        }
    }
    //後台首頁
    public function home()
	{
        //建立View
		return View::make('admin.home');
	}
    //會員管理
    public function member()
	{
        $data = DB::table('user')->get();
        View::share('data',$data);
        //建立View
		return View::make('admin.member');
	}
}