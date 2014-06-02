<?php

class APIController extends BaseController {

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
    //API
    public function api($apiName="")
    {
        //設定時區
        date_default_timezone_set('Asia/Taipei');
        //檢查是否有API名稱
        if(Empty($apiName)){
            return Redirect::to('/');
        }
        //return $apiName;
        //對應功能
        switch($apiName){
            //刷新並取得Token
            case "getToken":
                return $this->getToken();
                break;
            //檢查Token
            case "checkToken":
                return $this->checkToken();
                break;
            //取得角色ID
            case "getID":
                return $this->getID();
                break;
            default:
                return Redirect::to('/');
        }
    }
    //刷新並取得Token
    public function getToken()
	{
        //檢查是否要求強制附上遊戲ID
        $requireGameID = true;
        //檢查是否有帳號、密碼
        if(!Input::has("username") || !Input::has("password")){
            $result = Array(
                "result" => false,
                "error" => "Invalid Request",
            );
        }else{
            //檢查帳號密碼正確性
            $count = DB::table('user')->where('loginType','local')->where('username',Input::get('username'))->where('password',func::Hash(Input::get('password')))->count();
            if($count < 1){
                //帳號或密碼錯誤
                $result = Array(
                    "result" => false,
                    "error" => "Login Failed",
                );
            }else{
                //順利登入
                //取出資料
                $data = DB::table('user')->where('loginType','local')->where('username',Input::get('username'))->first();
                //檢查是否通過驗證
                if($data->group == "unverified"){
                    //未通過Email驗證
                    $result = Array(
                        "result" => false,
                        "error" => "Email Unverified",
                    );
                }else{
                    //順利登入且已通過Email驗證
                    //檢查是否有輸入遊戲ID
                    $count = 0;
                    if(Input::has("gameID")){
                        //檢查遊戲是否存在
                        $count = DB::table('game')->where('game',Input::get("gameID"))->count();
                    }
                    //檢查是否要求強制附上遊戲ID
                    if($requireGameID && Input::has("gameID")==false){
                        $result = Array(
                            "result" => false,
                            "error" => "Require gameID",
                        );
                    }else{
                        if($count < 1){
                            if(Input::has("gameID")){
                                //遊戲不存在
                                $result = Array(
                                    "result" => false,
                                    "error" => "Invalid gameID",
                                );
                            }else{
                                //未指定遊戲
                                //產生token
                                $token = func::RandomString(32);
                                $tokenDeadline = date_create();
                                date_add($tokenDeadline, date_interval_create_from_date_string('1 hours'));        //加一小時
                                //存入資料
                                DB::table('user')->where('loginType','local')->where('username',Input::get('username'))->update(array('token' => $token,'tokenDeadline' => $tokenDeadline));
                                //重新取出資料
                                $data = DB::table('user')->where('loginType','local')->where('username',Input::get('username'))->first();
                                //產生json
                                $result = Array(
                                    "result" => true,
                                    "username" => $data->username,
                                    "token" => $data->token,
                                    "tokenDeadline" => strtotime($data->tokenDeadline),
                                );
                            }
                        }else{
                            //檢查該帳號是否有該遊戲的角色ID
                            $count = DB::table('avatar')->where('username',Input::get('username'))->where('game',Input::get("gameID"))->count();
                            if($count < 1){
                                //該帳號沒有該遊戲的ID
                                $result = Array(
                                    "result" => false,
                                    "error" => "No ID",
                                );
                            }else{
                                //產生token
                                $token = func::RandomString(32);
                                $tokenDeadline = date_create();
                                date_add($tokenDeadline, date_interval_create_from_date_string('1 hours'));        //加一小時
                                //存入資料
                                DB::table('user')->where('loginType','local')->where('username',Input::get('username'))->update(array('token' => $token,'tokenDeadline' => $tokenDeadline));
                                //重新取出資料
                                $data = DB::table('user')->where('loginType','local')->where('username',Input::get('username'))->first();
                                //產生json
                                $result = Array(
                                    "result" => true,
                                    "username" => $data->username,
                                    "gameID" => Input::get("gameID"),
                                    "id" => member::getID(Input::get("gameID"),$data->username),
                                    "token" => $data->token,
                                    "tokenDeadline" => strtotime($data->tokenDeadline),
                                );
                            }
                        }
                    }
                }
            }
        }
        //印出JSON
        return Response::json($result);
	}
    //檢查Token
    public function checkToken()
    {
        //檢查是否有帳號及Token
        if(!Input::has("username") || !Input::has("token")){
            $result = Array(
                "result" => false,
                "error" => "Invalid Request",
            );
        }else{
            //檢查帳號正確性
            $count = DB::table('user')->where('loginType','local')->where('username',Input::get('username'))->count();
            if($count < 1){
                //無此帳號
                $result = Array(
                    "result" => false,
                    "error" => "Invalid username",
                );
            }else{
                //檢查該帳號的Token正確性
                $count = DB::table('user')->where('loginType','local')->where('username',Input::get('username'))->where('token',Input::get('token'))->count();
                if($count < 1){
                    //Token錯誤
                    $result = Array(
                        "result" => false,
                        "error" => "Invalid token",
                    );
                }else{
                    //取出資料
                    $data = DB::table('user')->where('loginType','local')->where('username',Input::get('username'))->first();
                    //檢查Token有效期限
                    $tokenDeadline = date_create($data->tokenDeadline);
                    $nowTime = date_create();
                    $diff=date_diff($nowTime,$tokenDeadline); //比較
                    if($diff->invert==1){
                        //Token過期
                        $result = Array(
                            "result" => false,
                            "error" => "Token expired",
                        );
                    }else{
                        //有效Token
                        $result = Array(
                            "result" => true,
                            "username" => $data->username,
                            "token" => $data->token,
                        );
                    }
                }
            }
        }
        //印出JSON
        return Response::json($result);
    }
    //取得角色ID
    public function getID()
    {
        //檢查是否有帳號及GameID
        if(!Input::has("username") || !Input::has("gameID")){
            $result = Array(
                "result" => false,
                "error" => "Invalid Request",
            );
        }else{
            //檢查帳號正確性
            $count = DB::table('user')->where('loginType','local')->where('username',Input::get('username'))->count();
            if($count < 1){
                //無此帳號
                $result = Array(
                    "result" => false,
                    "error" => "Invalid username",
                );
            }else{
                //檢查遊戲是否存在
                $count = DB::table('game')->where('game',Input::get("gameID"))->count();
                if($count < 1){
                    //遊戲不存在
                    $result = Array(
                        "result" => false,
                        "error" => "Invalid gameID",
                    );
                }else{
                    //檢查該帳號是否有該遊戲的角色ID
                    $count = DB::table('avatar')->where('username',Input::get('username'))->where('game',Input::get("gameID"))->count();
                    if($count < 1){
                        //該帳號沒有該遊戲的ID
                        $result = Array(
                            "result" => false,
                            "error" => "No ID",
                        );
                    }else{
                        $data = DB::table('avatar')->where('username',Input::get('username'))->where('game',Input::get("gameID"))->first();
                        $result = Array(
                            "result" => true,
                            "username" => $data->username,
                            "gameID" => $data->game,
                            "id" => $data->id,
                        );
                    }
                }
            }
        }
        //印出JSON
        return Response::json($result);
    }
}