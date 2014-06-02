<?php

class BaseController extends Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */

    //預設樣板
    //public $layout = 'layouts.default';
    
    public function __construct()
    {
        //檢查是否開啟OAuth
        if(Config::get('config.allowOAuth')){
            //FB驗證 初始化設定
            global $config,$facebook,$fbUserID,$fbProfile;
            fb::Initialize();
            //Google驗證 初始化設定
            global $google,$googleProfile;
            google::Initialize();
            google::Check();
        }
        
        //網站名稱
        View::share('sitename', Config::get('config.sitename'));
        //巡覽列
        $navbar['item_prefix'] = Config::get('navbar.item_prefix');
        $navbar['item_nologin'] = Config::get('navbar.item_nologin');
        $navbar['item_login'] = Config::get('navbar.item_login');
        $navbar['item_suffix'] = Config::get('navbar.item_suffix');
        if(member::check()){
            $navbar['all'] = array_merge($navbar['item_prefix'],$navbar['item_login'],$navbar['item_suffix']);
        }else{
            $navbar['all'] = array_merge($navbar['item_prefix'],$navbar['item_nologin'],$navbar['item_suffix']);
        }
        if(member::hasPerm("permAdmincp")){
            $navbar['all'] = array_merge($navbar['all'],Config::get('navbar.item_admin'));
        }
        View::share('navbar',$navbar['all']);
        //二級巡覽列
        //當前區域
        $tmpstr = explode("/",Route::current()->getUri());
        $zone = $tmpstr[0];
        $subNavbar = Config::get('navbar.sub_navber.'.$zone);
        View::share('zone',$zone);
        View::share('subNavbar',$subNavbar);
        
        //檢查第一次登入
        if(member::getType() == "facebook" || member::getType() == "google"){
            Session::forget('forceJump');
            $count = DB::table('user')->where('username',member::getEmail())->count();
            $countFacebook = DB::table('user')->where('loginType','facebook')->where('username',member::getEmail())->count();
            $countGoogle = DB::table('user')->where('loginType','google')->where('username',member::getEmail())->count();
            if($count < 1){
                //建立新帳號
                if(member::getType() == "facebook"){
                    $nickname = $fbProfile['name'];
                }else if(member::getType() == "google"){
                    $nickname = $googleProfile['name'];
                }
                //寫入資料
                DB::table('user')->insert(
                    array(
                        'username' => member::getEmail(),
                        'nickname' => $nickname,
                        'loginType' => member::getType(),
                        'group' => 'user',
                    )
                );
                    
                //設定訊息
                Session::put('jumpMsg','帳號建立完成');
                //設定強制重導向（完成登入動作之後）
                Session::put('forceJump','after');
            }else{
                $data = DB::table('user')->where('username',member::getEmail())->first();
                if(member::getType() == "facebook" && $countFacebook < 1){
                    //該帳號已存在，但並非以Facebook登入
                    //設定訊息
                    if($data->loginType == "local"){
                        Session::put('jumpMsg','此帳號為本地帳號，請使用帳號密碼登入');
                    }else if($data->loginType == "google"){
                        Session::put('jumpMsg','此帳號為Google帳號，請使用Google登入');
                    }
                    //設定強制重導向
                    Session::put('forceJump','before');
					//設定跳轉網址
					Session::put('jumpURL',URL::to('logout'));
                }else if(member::getType() == "google" && $countGoogle < 1){
                    //該帳號已存在，但並非以Google登入
                    //設定訊息
                    if($data->loginType == "local"){
                        Session::put('jumpMsg','此帳號為本地帳號，請使用帳號密碼登入');
                    }else if($data->loginType == "facebook"){
                        Session::put('jumpMsg','此帳號為Facebook帳號，請使用Facebook登入');
                    }
                    //設定強制重導向
                    Session::put('forceJump','before');
					//設定跳轉網址
					Session::put('jumpURL',URL::to('logout'));
                }
            }
        }
        //強制重導向
        //不使用強制重導向的路由
        $ignore = array("jump", "jumpBack", "logout");
        if(!in_array(Route::current()->getUri(),$ignore)){
            //根據需求決定一開始就重新導向，或是處理完才重新導向
            if (Session::get('forceJump') == 'before'){
                Session::forget('forceJump');
                $this->beforeFilter(function(){
                    return Redirect::to('jump');
                });
            }else if(Session::get('forceJump') == 'after'){
                Session::forget('forceJump');
                $this->afterFilter(function(){
                    return Redirect::to('jump');
                });
            }
        }
       
        
    }
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

}