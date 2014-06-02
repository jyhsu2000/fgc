<?php

class AjaxController extends BaseController {

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
    //Ajax
    public function ajax($ajaxName="",$arg="")
    {
        //檢查是否有Ajax名稱
        if(Empty($ajaxName)){
            return Redirect::to('/');
        }
        //檢查是否為Ajax請求
        if(!Request::ajax()){
            return Redirect::to('/');
        }
        //對應功能
        switch($ajaxName){
            //修改暱稱
            case "setNickname":
                return $this->setNickname();
                break;
            //修改角色ID
            case "setID":
                return $this->setID();
                break;
            //等待列表
            case "queue":
                return $this->queue($arg);
                break;
            //實況
            case "live":
                return $this->live($arg);
                break;
            default:
                return "<font color=\"red\">錯誤請求</font>";
        }
    }
    //修改暱稱
    public function setNickname()
	{
        //檢查是否登入
        if(!member::check()){
            return "發生錯誤，請重新登入";
        }
        //檢查有無輸入
        if(!Input::has('value')){
            return "請輸入新暱稱";
        }
        //更新暱稱
        DB::table('user')->where('username',member::getEmail())->update(array('nickname'=>Input::get('value')));
        //重新取得資料
        //$data = DB::table('user')->where('loginType','local')->where('username',Session::get('username'))->first();
        return member::getName();
	}
    //修改角色ID
    public function setID()
	{
        //檢查是否登入
        if(!member::check() || member::getType() != "local"){
            return "發生錯誤，請重新登入";
        }
        //檢查是否完成信箱驗證
        if(member::getGroup() == "unverified"){
            return "信箱未驗證";
        }
        //檢查有無輸入
        if(!Input::has('value')){
            return "發生錯誤，請輸入角色ID";
        }
        //檢查是否只有英數
        if(!ctype_alnum(Input::get('value'))){
            return "角色ID僅能包含英文字母與數字";
        }
        //遊戲ID
        if(!Input::has('game')){
            //沒有遊戲ID
            return "發生錯誤，未指定遊戲";
        }
        $gameID = Input::get('game');
        //檢查遊戲是否存在
        $count = DB::table('game')->where('game',$gameID)->count();
        if($count < 1){
            return "發生錯誤，遊戲不存在";
        }
        //檢查角色ID是否重複
        $count = DB::table('avatar')->where('username','<>',member::getEmail())->where('game',$gameID)->where('id',Input::get('value'))->count();
        if($count >= 1){
            return "此ID已有人使用";
        }
        //檢查原本有沒有角色ID
        $count = DB::table('avatar')->where('username',member::getEmail())->where('game',$gameID)->count();
        //更新角色ID
        if($count>=1){
            try{
                DB::table('avatar')->where('username',member::getEmail())->where('game',$gameID)->update(array('id'=>Input::get('value')));
            }
            catch (Exception $e){
                return "此ID正在遊玩中";
            }
        }else{
            DB::table('avatar')->insert(array(
                'username'=>member::getEmail(),
                'game'=>$gameID,
                'id'=>Input::get('value')
            ));
            DB::table('stats')->insert(array(
                'game'=>$gameID,
                'id'=>Input::get('value')
            ));
        }
        //重新取得資料
        $data = DB::table('avatar')->where('username',member::getEmail())->where('game',$gameID)->first();
        return $data->id;
	}
    //等待列表
    public function queue($gameID="")
	{
        //檢查是否指定遊戲
        if($gameID!=""){
            //檢查遊戲是否存在，且未隱藏
            $count = DB::table('game')->where('game',$gameID)->where('hide',0)->count();
            if($count < 1){
                //遊戲不存在
                return "遊戲不存在";
            }
            //讀取等待清單
            $data = DB::table('queue')->where('queue.game',$gameID)->join('game','queue.game','=','game.game')->where('game.hide',0)->get();
        }else{
            //讀取等待清單
            $data = DB::table('queue')->join('game','queue.game','=','game.game')->where('game.hide',0)->get();
        }
        $str = "";
        if(count($data)>0){
            foreach($data as $id => $item){
                $line = "";
                $line .= "<tr><td>";
                $line .= "<a href=\"" . URL::to('game/info/'.$item->game) . "\">";
                $line .= $item->gameName;
                $line .= "</a>";
                $line .= "</td><td>";
                $line .= "<a href=\"" . URL::to('gameID/'.$item->game.'/'.$item->id) . "\">";
                $line .= $item->id;
                $line .= "</a>";
                $line .= "</td><td>";
                $line .= $item->joinTime;
                $line .= "</td></tr>";
                $str .= $line;
            }
        }else{
            $str .= "<tr><td colspan=\"3\" style=\"text-align:center;\">";
            $str .= "目前等待人數為零";
            $str .= "</td></tr>";
        }
        return $str;
    }
    //實況
    public function live($rid="")
	{
        //檢查記錄是否存在
        $count = DB::table('game_record')->where('rid',$rid)->count();
        if($count==0){
            //記錄不存在
            return "記錄不存在";
        }
        $data = DB::table('game_record')->where('rid',$rid)->first();
        return nl2br($data->record);
    }
}