<?php

class MemberController extends BaseController {

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
    //登入
    public function login()
	{
        //檢查是否登入
        if(member::check()){
            return Redirect::to('/');
        }
        //取得前一頁的網址
        if(URL::previous() != URL::to('jump') && URL::previous() != URL::to('jumpBack') && URL::previous() != URL::to('login') && URL::previous() != URL::to('register')){
            Session::put('jumpURL',URL::previous());
        }
        //檢查是否開啟OAuth
            if(Config::get('config.allowOAuth')){
            //Facebook
            global $facebook;
            $fb_login_url = $facebook->getLoginUrl(array('scope' => 'email'));
            View::share('fb_login_url',$fb_login_url);
            //Google
            global $google;
            $GoogleAuthUrl = $google->createAuthUrl();
            View::share('GoogleAuthUrl',$GoogleAuthUrl);
        }
        //建立View
		return View::make('member.login');
	}
    //註冊
    public function register()
	{
        //檢查是否登入
        if(member::check()){
            return Redirect::to('/');
        }
        //取得前一頁的網址
        if(URL::previous() != URL::to('jump') && URL::previous() != URL::to('jumpBack') && !Session::has('jumpURL')){
            Session::put('jumpURL',URL::previous());
        }
        //是否允許註冊
        if(Config::get('config.allowRegister') == false){
            //設定訊息
            Session::put('jumpMsg','當前禁止註冊');
            //導向至跳轉頁面
            return Redirect::to('jump');
        }

        //建立View
		return View::make('member.register');
	}
    //重新導向
    public function redirect()
	{
        $action = Input::get('action');
        if($action == "login"){
            //登入
            $username = Input::get('username');
            $password = Input::get('password');
            $msg = "";            
            
            //驗證帳號
            $count = DB::table('user')->where('loginType','local')->where('username',Input::get('username'))->where('password',func::Hash(Input::get('password')))->count();
            if($count < 1){
                //設定訊息
                Session::put('jumpMsg','帳號或密碼錯誤');
                //導向至跳轉頁面
                return Redirect::to('jumpBack');
            }
            //登入帳號
            Session::put('username',Input::get('username'));
            Session::put('password',func::Hash(Input::get('password')));
            //儲存Cookie
            if(Input::get('remember')==true){
                //記住幾分鐘
                $rememberTime = 1440*30;
                Cookie::queue('username', Input::get('username'), $rememberTime);
                Cookie::queue('password', md5(func::Hash(Input::get('password'))), $rememberTime);
            }
            
            /*
            //＝＝＝＝＝ 外部NID驗證 Begin ＝＝＝＝＝
            //set POST variables
            $url = 'http://sdsweb.oit.fcu.edu.tw/coursequest/condition.jsp';
            $fields = array(
                                    'userID' => $username,
                                    'userPW' => $password
                            );

            //url-ify the data for the POST
            $fields_string='';
            foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
            rtrim($fields_string, '&');

            //open connection
            $ch = curl_init();

            //set the url, number of POST vars, POST data
            curl_setopt($ch,CURLOPT_URL, $url);
            curl_setopt($ch,CURLOPT_REFERER,"http://sdsweb.oit.fcu.edu.tw/coursequest/condition.jsp");
            curl_setopt($ch,CURLOPT_POST, count($fields));
            curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch,CURLOPT_HEADER, 0);

            //execute post
            $result = curl_exec($ch);
            $result_utf8 = iconv("big5","utf-8",$result);
            //close connection
            curl_close($ch);
            
            //＝＝＝＝＝ 外部NID驗證 End ＝＝＝＝＝

            $nid = trim(str_replace("登入：","",strstr(strstr($result_utf8,"登入："),"&nbsp;&nbsp;",true)));
            
            $msg .= "<h3><font color=\"red\">";
            if($nid){
                $msg .= "<h3>歡迎回來：" . $nid;
            }else{
                $msg .= "帳號或密碼錯誤";
            }
            $msg .= "</font></h3>";
            
            View::share('msg', $msg);
            //建立View
            $this->layout->content = View::make('error');
            */
            
            
            //設定訊息
            Session::put('jumpMsg','登入完成');
            //導向至跳轉頁面
            return Redirect::to('jump');
            
        }else if($action == "register"){
            //註冊
            
            //＝＝＝＝＝＝＝＝＝＝檢查＝＝＝＝＝＝＝＝＝＝
            //檢查帳號與密碼皆有輸入
            if((Input::has('username') && Input::has('password') && Input::has('password2')) == false){
                Session::put('jumpMsg','請輸入信箱及密碼');
                return Redirect::to('jumpBack');
            }
            //檢查Email格式
            if(!func::isEmail(Input::get('username'))){
                Session::put('jumpMsg','信箱格式有誤');
                return Redirect::to('jumpBack');
            }
            //檢查帳號是否重複
            //$count = DB::table('user')->where('loginType','local')->where('username',Input::get('username'))->count();
            $count = DB::table('user')->where('username',Input::get('username'))->count();
            if($count >= 1){
                //取出資料
                $data = DB::table('user')->where('username',Input::get('username'))->first();
                //判斷不同登入方式
                if($data->loginType == "local"){
                    Session::put('jumpMsg','此帳號已被註冊');
                }else if($data->loginType == "facebook"){
                    Session::put('jumpMsg','此帳號已存在，請直接使用Facebook登入');
                }else if($data->loginType == "google"){
                    Session::put('jumpMsg','此帳號已存在，請直接使用Google登入');
                }
                return Redirect::to('jumpBack');
            }
            //檢查密碼是否相同
            if(Input::get('password') != Input::get('password2')){
                Session::put('jumpMsg','兩次密碼輸入不相同');
                return Redirect::to('jumpBack');
            }
            //檢查帳號與密碼相重
            if(Input::get('username') == Input::get('password')){
                Session::put('jumpMsg','密碼不得與帳號相同');
                return Redirect::to('jumpBack');
            }
            //＝＝＝＝＝＝＝＝＝＝產生驗證碼＝＝＝＝＝＝＝＝＝＝
            //以信箱與亂數再加密，確保不會重複
            $verifyCode = md5(Input::get('username') . func::RandomString(32));
            //＝＝＝＝＝＝＝＝＝＝寫入資料＝＝＝＝＝＝＝＝＝＝
            //暱稱預設為信箱「@」之前的部份
            $tmpstr = explode("@",Input::get('username'));
            $nickname = $tmpstr[0];
            DB::table('user')->insert(
                array(
                    'username' => Input::get('username'),
                    'password' => func::Hash(Input::get('password')),
                    'nickname' => $nickname,
                    'loginType' => 'local',
                    'verifyCode' => $verifyCode,
                    'group' => 'unverified',
                )
            );
            //＝＝＝＝＝＝＝＝＝＝發送驗證郵件＝＝＝＝＝＝＝＝＝＝
            $data = array('verifyCode' => $verifyCode);
            Mail::send('emails.welcome', $data, function($message)
            {
                $message->to(Input::get('username'), Input::get('username'))->subject('[屯門遊樂局]帳號驗證');
            });
            
            
            
            //自動登入
            Session::put('username',Input::get('username'));
            Session::put('password',func::Hash(Input::get('password')));
            //設定訊息
            Session::put('jumpMsg','驗證郵件已寄出');
            //導向至跳轉頁面
            return Redirect::to('jump');
        }else if($action == "changePassword"){
            //修改密碼
            //＝＝＝＝＝＝＝＝＝＝檢查＝＝＝＝＝＝＝＝＝＝
            //檢查舊密碼是否正確
            $count = DB::table('user')->where('loginType','local')->where('username',Session::get('username'))->where('password',func::Hash(Input::get('password')))->count();
            if($count < 1){
                Session::put('jumpMsg','舊密碼輸入錯誤');
                return Redirect::to('jumpBack');
            }
            //檢查密碼是否相同
            if(Input::get('newPassword') != Input::get('newPassword2')){
                Session::put('jumpMsg','兩次密碼輸入不相同');
                return Redirect::to('jumpBack');
            }
            //檢查帳號與密碼相重
            if(Session::get('username') == Input::get('newPassword')){
                Session::put('jumpMsg','密碼不得與帳號相同');
                return Redirect::to('jumpBack');
            }
            //＝＝＝＝＝＝＝＝＝＝更新資料＝＝＝＝＝＝＝＝＝＝
            //更新密碼
            DB::table('user')->where('loginType','local')->where('username',Session::get('username'))->update(array('password'=>func::Hash(Input::get('newPassword'))));
            //更新Session
            Session::put('password',func::Hash(Input::get('newPassword')));
            
            //設定跳轉網址
            Session::put('jumpURL',URL::to('profile'));
            //設定訊息
            Session::put('jumpMsg','密碼已修改');
            //導向至跳轉頁面
            return Redirect::to('jump');
		}else if($action == "resendVerifyCode"){
			//重新發送驗證碼
		    //＝＝＝＝＝＝＝＝＝＝檢查＝＝＝＝＝＝＝＝＝＝
            //檢查帳號是否未驗證
            if(member::getGroup() != 'unverified'){
                //設定訊息
				Session::put('jumpMsg','此帳號已完成驗證，無須重新驗證');
				//導向至跳轉頁面
				return Redirect::to('jump');
            }
            //＝＝＝＝＝＝＝＝＝＝產生驗證碼＝＝＝＝＝＝＝＝＝＝
            //以信箱與亂數再加密，確保不會重複
            $verifyCode = md5(member::getEmail() . func::RandomString(32));
            //＝＝＝＝＝＝＝＝＝＝更新資料＝＝＝＝＝＝＝＝＝＝
            DB::table('user')->where('loginType','local')->where('username',member::getEmail())->where('group','unverified')->update(array('verifyCode' => $verifyCode));
            //＝＝＝＝＝＝＝＝＝＝發送驗證郵件＝＝＝＝＝＝＝＝＝＝
            $data = array('verifyCode' => $verifyCode);
            Mail::send('emails.welcome', $data, function($message)
            {
                $message->to(member::getEmail(), member::getEmail())->subject('[屯門遊樂局]帳號驗證');
            });
            //設定訊息
            Session::put('jumpMsg','驗證郵件已寄出');
            //導向至跳轉頁面
            return Redirect::to('jump');
        }else if($action == "findPassword"){
            //檢查是否登入
            if(member::check()){
                return Redirect::to('/');
            }
            //找回密碼：發送重設密碼連結
            //＝＝＝＝＝＝＝＝＝＝檢查＝＝＝＝＝＝＝＝＝＝
            //檢查Email格式
            if(!func::isEmail(Input::get('username'))){
                Session::put('jumpMsg','信箱格式有誤');
                return Redirect::to('jumpBack');
            }
            //檢查該信箱是否有註冊
            $count = DB::table('user')->where('loginType','local')->where('username',Input::get('username'))->count();
            if($count < 1){
                Session::put('jumpMsg','該信箱未註冊，請確認信箱是否輸入正確');
                return Redirect::to('jumpBack');
            }
            //＝＝＝＝＝＝＝＝＝＝產生驗證碼＝＝＝＝＝＝＝＝＝＝
            //以信箱與亂數再加密，確保不會重複
            $findPwdCode = md5(Input::get('username') . func::RandomString(32));
            //＝＝＝＝＝＝＝＝＝＝更新資料＝＝＝＝＝＝＝＝＝＝
            DB::table('user')->where('loginType','local')->where('username',Input::get('username'))->update(array('findPwdCode' => $findPwdCode,'findPwdTime' => date('Y-m-d H:i:s')));
            //＝＝＝＝＝＝＝＝＝＝發送重設密碼連結郵件＝＝＝＝＝＝＝＝＝＝
            $data = array('findPwdCode' => $findPwdCode);
            Mail::send('emails.findPassword', $data, function($message)
            {
                $message->to(Input::get('username'), Input::get('username'))->subject('[屯門遊樂局]找回密碼');
            });
            //設定訊息
            Session::put('jumpMsg','重設密碼連結已發送至信箱');
            //導向至跳轉頁面
            return Redirect::to('jump');
        }else if($action == "resetPassword"){
            //重設密碼
            //＝＝＝＝＝＝＝＝＝＝檢查＝＝＝＝＝＝＝＝＝＝
            //檢查是否未登入並具有驗證代碼
            if(!Input::has('findPwdCode') || member::check()){
                return Redirect::to('/');
            }
            //確認驗證碼有效性
            $count = DB::table('user')->where('loginType','local')->where('findPwdCode',Input::get('findPwdCode'))->count();
            if($count < 1){
                Session::put('jumpMsg','此連結無效');
                return Redirect::to('jump');
            }
            //確認驗證碼期限
            $result = DB::table('user')->where('loginType','local')->where('findPwdCode',Input::get('findPwdCode'))->first();
            $findPwdTime = date_create($result->findPwdTime);
            date_add($findPwdTime, date_interval_create_from_date_string('3 days'));        //加三天
            $nowTime = date_create();
            $diff=date_diff($nowTime,$findPwdTime); //比較
            if($diff->invert==1){
                Session::put('jumpMsg','此連結已過期');
                return Redirect::to('jump');
            }
            //檢查密碼是否相同
            if(Input::get('newPassword') != Input::get('newPassword2')){
                Session::put('jumpMsg','兩次密碼輸入不相同');
                return Redirect::to('jumpBack');
            }
            //檢查帳號與密碼相重
            if($result->username == Input::get('newPassword')){
                Session::put('jumpMsg','密碼不得與帳號相同');
                return Redirect::to('jumpBack');
            }
            //＝＝＝＝＝＝＝＝＝＝更新資料＝＝＝＝＝＝＝＝＝＝
            //更新密碼並清除重設密碼驗證代碼
            DB::table('user')->where('loginType','local')->where('username',$result->username)->update(array('password'=>func::Hash(Input::get('newPassword')),'findPwdCode'=>''));
            //設定訊息
            Session::put('jumpMsg','密碼重新設定完成，請重新登入');
            //導向至跳轉頁面
            return Redirect::to('jump');
        }else if($action == "mobileLogin"){
            //行動裝置登入（測試用）
            return func::RandomString(32);
        }else if($action == "editProfile"){
            //編輯個人檔案
            //＝＝＝＝＝＝＝＝＝＝檢查＝＝＝＝＝＝＝＝＝＝
            //檢查是否有編輯個人檔案權限
            if(!member::hasPerm("editProfile")){
                return Redirect::to('/');
            }
            //檢查是否有指定uid
            if(!Input::has('uid') || !func::isInt(Input::get('uid'))){
                return Redirect::to('/');
            }
            $uid = Input::get('uid');
            //檢查uid是否有效
            $count = DB::table('user')->where('uid',$uid)->count();
            if($count == 0){
                //設定訊息
                Session::put('jumpMsg','帳號不存在');
                //導向至跳轉頁面
                return Redirect::to('jumpBack');
            }
            //取得原資料
            $data = DB::table('user')->where('uid',$uid)->first();
            //計算管理員人數
            $adminAmount = DB::table('user')->where('group','admin')->count();
            //管理員只有一人，該帳號原本又是管理員
            if($adminAmount == 1 && $data->group == "admin"){
                if(Input::get('group') != "admin"){
                    //設定訊息
                    Session::put('jumpMsg','你是僅存的管理員，不能讓管理員人數為零');
                    //導向至跳轉頁面
                    return Redirect::to('jumpBack');
                }
            }
            
            //＝＝＝＝＝＝＝＝＝＝更新資料＝＝＝＝＝＝＝＝＝＝
            DB::table('user')->where('uid',$uid)->update(
                array(
                    'nickname' => Input::get('nickname'),
                    'loginType' => Input::get('loginType'),
                    'group' => Input::get('group'),
                )
            );
            //檢查是否輸入新密碼
            if(Input::has('password')){
                //檢查帳號與密碼相重
                if(Input::get('username') == Input::get('password')){
                    Session::put('jumpMsg','密碼不得與帳號相同');
                    return Redirect::to('jumpBack');
                }
                //更新密碼
                DB::table('user')->where('uid',$uid)->update(
                    array(
                        'password' => func::Hash(Input::get('password')),
                    )
                );
            }
            //設定訊息
            Session::put('jumpMsg','資料已更新');
            Session::put('jumpURL',URL::to('profile/'.$uid));
            //導向至跳轉頁面
            return Redirect::to('jump');
        }else{
            //重新導向
            return Redirect::to('/');
        }
	}
    //會員驗證（驗證碼）
    public function verify($verifyCode="")
    {
        if(Empty($verifyCode)){
            return Redirect::to('/');
        }
        //確認驗證碼有效性
        $count = DB::table('user')->where('loginType','local')->where('verifyCode',$verifyCode)->count();
        if($count < 1){
            Session::put('jumpMsg','驗證碼無效');
            return Redirect::to('jump');
        }
        //確認該會員尚未驗證
        $count = DB::table('user')->where('loginType','local')->where('verifyCode',$verifyCode)->where('group','unverified')->count();
        if($count < 1){
            Session::put('jumpMsg','該帳號已驗證');
            return Redirect::to('jump');
        }
        //更新會員資料
        DB::table('user')->where('loginType','local')->where('verifyCode',$verifyCode)->where('group','unverified')->update(array('group'=>'user'));
        //設定訊息
        Session::put('jumpMsg','驗證完成');
        //導向至跳轉頁面
        return Redirect::to('jump');
    }
    //Google驗證
    public function googleOAuth()
	{
        //Google OAuth 驗證
        google::Auth();
        //重新導向
        return Redirect::to('/');
	}
    //登出
    public function logout()
	{
        //檢查是否開啟OAuth
        if(Config::get('config.allowOAuth')){
            //外部帳號登出
            fb::logout();
        }
        //清除Session
        Session::flush();
        //清除Cookies
        Cookie::queue('username','');
        Cookie::queue('password','');
        //重新導向
        return Redirect::to('/');
	}
    //個人檔案
    public function profile($uid="")
	{
        //檢查是否登入
        if(member::check() == false){
            //設定跳轉網址
            Session::put('jumpURL',Request::url());
            //導向至登入頁面
            return Redirect::to('login');
        }
        //檢查是否有指定uid
        if(!Empty($uid) && func::isInt($uid)){
            //查看他人資料
            //檢查uid是否有效
            $count = DB::table('user')->where('uid',$uid)->count();
            if($count == 0){
                //設定訊息
                Session::put('jumpMsg','帳號不存在');
                //導向至跳轉頁面
                return Redirect::to('jumpBack');
            }
            $data = DB::table('user')->where('uid',$uid)->first();
            View::share('uid',$uid);
            View::share('email',$data->username);
        }else{
            //查看自己資料
            $data = DB::table('user')->where('username',member::getEmail())->first();
            View::share('uid',$data->uid);
            View::share('email','');
        }
        //角色ID
        $idList = DB::table('avatar')->where('username',$data->username)->leftJoin('game','avatar.game','=','game.game')->get();
        View::share('idList',$idList);
        //建立View
		return View::make('member.profile');
	}
    //由遊戲ID連到個人檔案
    public function gameID($game="",$id="")
	{
        //檢查是否有指定遊戲與id
        if(!Empty($game) && !Empty($id)){
            $count = DB::table('avatar')->where('game',$game)->where('id',$id)->count();
            if($count==0){
                //設定訊息
                Session::put('jumpMsg','遊戲或ID不存在');
                //導向至跳轉頁面
                return Redirect::to('jumpBack');
            }
            $data = DB::table('avatar')->where('game',$game)->where('id',$id)->leftJoin('user','user.username','=','avatar.username')->first();
            //導向至個人資料頁面
            return Redirect::to('profile/'.$data->uid);
        }else{
            return Redirect::to('/');
        }
	}
    //編輯個人檔案
    public function editProfile($uid="")
	{
        //檢查是否登入
        if(member::check() == false){
            //設定跳轉網址
            Session::put('jumpURL',Request::url());
            //導向至登入頁面
            return Redirect::to('login');
        }
        //檢查是否有編輯個人檔案權限
        if(!member::hasPerm("editProfile")){
            return Redirect::to('/');
        }
        //檢查是否有指定uid
        if(!Empty($uid) && func::isInt($uid)){
            //查看他人資料
            //檢查uid是否有效
            $count = DB::table('user')->where('uid',$uid)->count();
            if($count == 0){
                //設定訊息
                Session::put('jumpMsg','帳號不存在');
                //導向至跳轉頁面
                return Redirect::to('jumpBack');
            }
            $data = DB::table('user')->where('uid',$uid)->first();
            //用戶組清單
            $group = DB::table('group')->get();
            View::share('group',$group);
            View::share('uid',$uid);
            View::share('email',$data->username);
        }else{
            return Redirect::to('/');
        }
        //建立View
		return View::make('member.editProfile');
	}
    //修改密碼
    public function changePassword()
	{
        //檢查是否登入
        if(member::check() == false){
            //設定跳轉網址
            Session::put('jumpURL',Request::url());
            //導向至跳轉頁面
            return Redirect::to('login');
        }
        //檢查帳號類型
        if(member::getType() != 'local'){
            //設定訊息
            Session::put('jumpMsg','非本地帳號，無法在本站修改密碼');
            //導向至跳轉頁面
            return Redirect::to('jump');
        }
        //建立View
		return View::make('member.changePassword');
	}
    //重新發送驗證碼
    public function resendVerifyCode()
	{
        //檢查是否登入
        if(member::check() == false){
            //設定跳轉網址
            Session::put('jumpURL',Request::url());
            //導向至跳轉頁面
            return Redirect::to('login');
        }
        //檢查帳號類型
        if(member::getType() != 'local'){
            //設定訊息
            Session::put('jumpMsg','非本地帳號，無法在本站修改密碼');
            //導向至跳轉頁面
            return Redirect::to('jump');
        }
		//檢查帳號群組
		if(member::getGroup() != 'unverified'){
			//設定訊息
            Session::put('jumpMsg','此帳號已完成驗證，無須重新驗證');
            //導向至跳轉頁面
            return Redirect::to('jump');
		}
        //建立View
		return View::make('member.resendVerifyCode');
	}
    //找回密碼
    public function findPassword()
    {
        //檢查是否登入
        if(member::check()){
            return Redirect::to('/');
        }
        //建立View
		return View::make('member.findPassword');
    }
    //重設密碼
    public function resetPassword($findPwdCode="")
    {
        //檢查是否未登入並具有驗證代碼
        if(Empty($findPwdCode) || member::check()){
            return Redirect::to('/');
        }
        //確認驗證碼有效性
        $count = DB::table('user')->where('loginType','local')->where('findPwdCode',$findPwdCode)->count();
        if($count < 1){
            Session::put('jumpMsg','此連結無效');
            return Redirect::to('jump');
        }
        //確認驗證碼期限
        $result = DB::table('user')->where('loginType','local')->where('findPwdCode',$findPwdCode)->first();
        $findPwdTime = date_create($result->findPwdTime);
        date_add($findPwdTime, date_interval_create_from_date_string('3 days'));        //加三天
        $nowTime = date_create();
        $diff=date_diff($nowTime,$findPwdTime); //比較
        if($diff->invert==1){
            Session::put('jumpMsg','此連結已過期');
            return Redirect::to('jump');
        }
        //變數
        View::share('username',$result->username);
        View::share('findPwdCode',$result->findPwdCode);
        //建立View
		return View::make('member.resetPassword');
    }
}