<?php

class GameController extends BaseController {

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
    //Game
    public function game($method="",$arg="")
    {
		//檢查是否有method
		if(Empty($method)){
			//顯示遊戲清單
			return $this->showList();
		}else if($method=="info" || $method=="edit"){
			//顯示公告內容或編輯公告
			//檢查是否有gameID名稱
			if(Empty($arg)){
				return Redirect::to('game');
			}else if(ctype_alnum($arg)){
                if($method=="info"){
                    //顯示遊戲資訊
                    return $this->show($arg);
                }else if($method=="edit"){
                    //編輯遊戲
                    return $this->edit($arg);
                }
			}else{
				return Redirect::to('game');
			}
		}else if($method=="new"){
            //新增遊戲
            return $this->add();
        }else if($method=="delete"){
            //刪除遊戲
            return $this->delete($arg);
        }else if($method=="redirect"){
            //處理各種請求
            return $this->redirect();
        }else if($method=="queue"){
            //等待列表
            return $this->queue($arg);
        }else if($method=="rank"){
            //排行榜
            return $this->rank($arg);
        }else if($method=="live" || $method=="record"){
			//實況或對戰記錄
			//檢查是否有gameID名稱
			if(Empty($arg) || ctype_alnum($arg)){
                if($method=="live"){
                    //實況
                    return $this->live($arg);
                }else if($method=="record"){
                    //對戰記錄
                    return $this->record($arg);
                }
			}else{
				return Redirect::to('game');
			}
		}else{
			return Redirect::to('game');
		}
        
    }
    //遊戲清單
    public function showList()
	{
		//讀取遊戲清單
		$data = DB::table('game')->get();
        View::share('data',$data);
        //讀取相關公告清單
        $news = array();
        foreach($data as $id => $item){
            $news[$item->game] = DB::table('bulletin')->orderBy('bid', 'desc')->where('game',$item->game)->first();
        }
        View::share('news',$news);
        //GM清單
        $gm = array();
        foreach($data as $id => $item){
            $gm[$item->game] = DB::table('gm')->where('game',$item->game)->leftJoin('user','gm.uid','=','user.uid')->get();
        }
        View::share('gm',$gm);
        //建立View
		return View::make('game.list');
	}
    //遊戲資訊
    public function show($gameID="")
	{
		//檢查遊戲是否存在
		$count = DB::table('game')->where('game',$gameID)->count();
		if($count < 1){
			//設定訊息
			Session::put('jumpMsg','遊戲不存在');
			Session::put('jumpURL',URL::to('game'));
			//導向至跳轉頁面
			return Redirect::to('jump');
		}
        //讀取遊戲
		$data = DB::table('game')->where('game',$gameID)->first();
        View::share('data',$data);
        //檢查是否隱藏
        if($data->hide==1 && !member::isGM($data->game)){
			return Redirect::to('game');
        }
        //讀取相關公告清單
		$amount = 5;
		$news = DB::table('bulletin')->orderBy('bid', 'desc')->where('game',$gameID)->take($amount)->get();
        View::share('news',$news);
        //GM清單
        $gm = DB::table('gm')->where('game',$gameID)->leftJoin('user','gm.uid','=','user.uid')->get();
        View::share('gm',$gm);
        //建立View
		return View::make('game.show');
	}
    //編輯遊戲
    public function edit($gameID="")
	{
        //檢查是否有編輯遊戲權限
        if(!member::isGM($gameID)){
            return Redirect::to('game');
        }
		//檢查遊戲是否存在
		$count = DB::table('game')->where('game',$gameID)->count();
		if($count < 1){
			//設定訊息
			Session::put('jumpMsg','遊戲不存在');
			Session::put('jumpURL',URL::to('game'));
			//導向至跳轉頁面
			return Redirect::to('jump');
		}
        //讀取遊戲
		$data = DB::table('game')->where('game',$gameID)->first();
        View::share('data',$data);
        if(member::hasPerm("editGame")){
            //讀取gm清單
            $gmList = DB::table('gm')->where('game',$gameID)->lists('uid');
            $gm = array(); 
            foreach($gmList as $id => $item){
                //檢查uid是否存在
                $count = DB::table('user')->where('uid',$item)->count();
                if($count>0){
                    $data = DB::table('user')->where('uid',$item)->first();
                    $gm[] = $data->username;
                }
            }
            $gm = implode("\n",$gm);
            View::share('gm',$gm);
        }
        View::share('type',"edit");
        //建立View
		return View::make('game.edit');
	}
    //新增遊戲
    public function add()
	{
        //檢查是否有編輯遊戲權限
        if(!member::hasPerm("editGame")){
            return Redirect::to('game');
        }
        View::share('type',"new");
        //建立View
		return View::make('game.edit');
	}
    //刪除遊戲
    public function delete($gameID=0)
	{
        //檢查是否有編輯遊戲權限
        if(!member::hasPerm("editGame")){
            return Redirect::to('game');
        }
		//檢查遊戲是否存在
		$count = DB::table('game')->where('game',$gameID)->count();
		if($count < 1){
			//設定訊息
			Session::put('jumpMsg','遊戲不存在');
			Session::put('jumpURL',URL::to('game'));
			//導向至跳轉頁面
			return Redirect::to('jump');
		}
        //讀取遊戲
		$data = DB::table('game')->where('game',$gameID)->first();
        View::share('data',$data);
        //建立View
		return View::make('game.delete');
	}
    //處理各種請求
    public function redirect()
	{
        //檢查是否有編輯遊戲權限
        if(!member::isGM(Input::get('game'))){
            return Redirect::to('game');
        }
        //請求類型
        if(!Input::has('action')){
            //未指定動作類型
            //設定訊息
            Session::put('jumpMsg','未指定動作類型');
            Session::put('jumpURL',URL::to('game'));
            //導向至跳轉頁面
            return Redirect::to('jump');
        }else if(Input::get('action') == "edit"){
            //編輯遊戲
            //遊戲ID
            if(!Input::has('game')){
                //沒有遊戲ID
                //設定訊息
                Session::put('jumpMsg','未指定遊戲ID');
                Session::put('jumpURL',URL::to('game'));
                //導向至跳轉頁面
                return Redirect::to('jump');
            }
            $gameID = Input::get('game');
            //檢查遊戲是否存在
            $count = DB::table('game')->where('game',$gameID)->count();
            if($count < 1){
                //設定訊息
                Session::put('jumpMsg','遊戲不存在');
                Session::put('jumpURL',URL::to('game'));
                //導向至跳轉頁面
                return Redirect::to('jump');
            }
            //檢查是否有遊戲名稱
            if(!Input::has('gameName') || strip_tags(Input::get('gameName'))==""){
                //設定訊息
                Session::put('jumpMsg','遊戲名稱不得空白');
                //導向至跳轉頁面
                return Redirect::to('jumpBack');
            }
            //更新遊戲
            $hide = (Input::has('hide')) ? 1 : 0 ;
            DB::table('game')->where('game',$gameID)->update(array(
                'gameName'=>strip_tags(Input::get('gameName')),
                'downloadLink'=>strip_tags(Input::get('downloadLink')),
                'shortInfo'=>strip_tags(func::keepXLines(Input::get('shortInfo'),3)),
                'information'=>func::closeHtmlTags(Input::get('information')),
                'hide'=>$hide,
            ));
            if(member::hasPerm("editGame")){
                //更新GM
                //清除原有GM資料
                DB::table('gm')->where('game',$gameID)->delete();
                //放入新的GM清單
                if(Input::has('gm')){
                    //分割每一行
                    $gmList = explode("\n",str_replace("\r","",Input::get('gm')));
                    //防止重複
                    $gmList = array_unique($gmList);
                    foreach($gmList as $id => $item){
                        //檢查username是否存在
                        $count = DB::table('user')->where('username',$item)->count();
                        if($count > 0){
                            $data = DB::table('user')->where('username',$item)->first();
                            DB::table('gm')->where('game',$gameID)->insert(array(
                                'game'=>$gameID,
                                'uid'=>$data->uid,
                            ));
                        }
                    }
                }
                //遊戲的等待清單（不存在時重建）
                $tableName = "queue_" . $gameID;
                if(!Schema::hasTable($tableName)){
                    Schema::dropIfExists($tableName);
                    Schema::create($tableName, function($table)
                    {
                        $table->engine = 'InnoDB';
                        $table->increments('qid');
                        $table->string('host', 32);
                        $table->string('client', 32);
                        $table->timestamp('joinTime')->default(DB::raw('CURRENT_TIMESTAMP'));
                        $table->foreign('host')
                          ->references('id')->on('avatar')
                          ->onDelete('cascade');
                        $table->foreign('client')
                          ->references('id')->on('avatar')
                          ->onDelete('cascade');
                    });
                }
            }
            //設定訊息
            Session::put('jumpMsg','編輯完成');
            Session::put('jumpURL',URL::to('game/info/'.$gameID));
            //導向至跳轉頁面
            return Redirect::to('jump');
        }else if(Input::get('action') == "new"){
            //新增遊戲
            //檢查是否有遊戲ID
            if(!Input::has('game') || strip_tags(Input::get('game'))==""){
                //設定訊息
                Session::put('jumpMsg','遊戲ID不得空白');
                //導向至跳轉頁面
                return Redirect::to('jumpBack');
            }
            $gameID = Input::get('game');
            //檢查遊戲是否存在
            $count = DB::table('game')->where('game',$gameID)->count();
            if($count >= 1){
                //設定訊息
                Session::put('jumpMsg','遊戲ID重複');
                //導向至跳轉頁面
                return Redirect::to('jumpBack');
            }
            //檢查是否有遊戲名稱
            if(!Input::has('gameName') || strip_tags(Input::get('gameName'))==""){
                //設定訊息
                Session::put('jumpMsg','遊戲名稱不得空白');
                //導向至跳轉頁面
                return Redirect::to('jumpBack');
            }
            //新增遊戲
            $hide = (Input::has('hide')) ? 1 : 0 ;
            DB::table('game')->where('game',$gameID)->insert(array(
                'game'=>strip_tags(Input::get('game')),
                'gameName'=>strip_tags(Input::get('gameName')),
                'downloadLink'=>strip_tags(Input::get('downloadLink')),
                'shortInfo'=>func::keepXLines(strip_tags(Input::get('shortInfo')),3),
                'information'=>func::closeHtmlTags(Input::get('information')),
                'hide'=>$hide,
            ));
            //更新GM
            //放入新的GM清單
            if(Input::has('gm')){
                //分割每一行
                $gmList = explode("\n",str_replace("\r","",Input::get('gm')));
                //防止重複
                $gmList = array_unique($gmList);
                foreach($gmList as $id => $item){
                    //檢查username是否存在
                    $count = DB::table('user')->where('username',$item)->count();
                    if($count > 0){
                        $data = DB::table('user')->where('username',$item)->first();
                        DB::table('gm')->where('game',$gameID)->insert(array(
                            'game'=>$gameID,
                            'uid'=>$data->uid,
                        ));
                    }
                }
            }
            //遊戲的等待清單
            $tableName = "queue_" . $gameID;
            Schema::dropIfExists($tableName);
            Schema::create($tableName, function($table)
            {
                $table->engine = 'InnoDB';
                $table->increments('qid');
                $table->string('host', 32);
                $table->string('client', 32);
                $table->timestamp('joinTime')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->foreign('host')
                  ->references('id')->on('avatar')
                  ->onDelete('cascade');
                $table->foreign('client')
                  ->references('id')->on('avatar')
                  ->onDelete('cascade');
            });
            //設定訊息
            Session::put('jumpMsg','新增完成');
            Session::put('jumpURL',URL::to('game/info/'.$gameID));
            //導向至跳轉頁面
            return Redirect::to('jump');
        }else if(Input::get('action') == "delete"){
            //刪除遊戲
            //遊戲ID
            if(!Input::has('game')){
                //沒有遊戲ID
                //設定訊息
                Session::put('jumpMsg','未指定遊戲ID');
                Session::put('jumpURL',URL::to('game'));
                //導向至跳轉頁面
                return Redirect::to('jump');
            }
            $gameID = Input::get('game');
            //檢查遊戲是否存在
            $count = DB::table('game')->where('game',$gameID)->count();
            if($count < 1){
                //設定訊息
                Session::put('jumpMsg','遊戲不存在');
                Session::put('jumpURL',URL::to('game'));
                //導向至跳轉頁面
                return Redirect::to('jump');
            }
            //刪除遊戲
            DB::table('game')->where('game',$gameID)->delete();
            //遊戲的等待清單
            $tableName = "queue_" . $gameID;
            Schema::dropIfExists($tableName);
            //設定訊息
            Session::put('jumpMsg','已刪除');
            Session::put('jumpURL',URL::to('game'));
            //導向至跳轉頁面
            return Redirect::to('jump');
        }else{
            //未定義操作
            //設定訊息
            Session::put('jumpMsg','未定義操作');
            Session::put('jumpURL',URL::to('game'));
            //導向至跳轉頁面
            return Redirect::to('jump');
        }
	}
    //等待列表
    public function queue($gameID="")
	{
        if($gameID!=""){
            //有指定遊戲
            //檢查遊戲是否存在，且未隱藏
            $count = DB::table('game')->where('game',$gameID)->where('hide',0)->count();
            if($count < 1){
                //遊戲不存在
                return Redirect::to('game/queue');
            }
            //設定ajax取得網址
            $ajaxURL = URL::to('ajax/queue/' . $gameID);
        }else{
            //未指定遊戲
            //設定ajax取得網址
            $ajaxURL = URL::to('ajax/queue');
        }
        View::share('ajaxURL',$ajaxURL);
        //指定的遊戲ID
        View::share('gameID',$gameID);
        //遊戲清單
        $gameList = DB::table('game')->where('hide',0)->get();
        View::share('gameList',$gameList);
        //建立View
		return View::make('game.queue');
    }
    //排行榜
    public function rank($gameID="")
	{
        if($gameID!=""){
            //有指定遊戲
            //檢查遊戲是否存在，且未隱藏
            $count = DB::table('game')->where('game',$gameID)->where('hide',0)->count();
            if($count < 1){
                //遊戲不存在
                return Redirect::to('game/rank');
            }
            //排行榜
            $stats = DB::table('stats')->select(DB::raw('*,(winTime/time) as rank'))->where('game',$gameID)->where('time','>',0)->orderBy('rank','desc')->get();
            View::share('stats',$stats);
        }
        //指定的遊戲ID
        View::share('gameID',$gameID);
        //遊戲清單
        $gameList = DB::table('game')->where('hide',0)->get();
        View::share('gameList',$gameList);
        //建立View
		return View::make('game.rank');
    }
    //實況
    public function live($arg="")
	{
        if($arg=="" && Input::has('rid') && func::isInt(Input::get('rid'))){
            //觀戰
            //檢查記錄是否存在
            $count = DB::table('game_record')->where('rid',Input::get('rid'))->count();
            if($count==0){
                //記錄不存在
                //設定訊息
                Session::put('jumpMsg','記錄不存在');
                Session::put('jumpURL',URL::to('game/live'));
                //導向至跳轉頁面
                return Redirect::to('jump');
            }
            //取得記錄資料
            $data = DB::table('game_record')->where('rid',Input::get('rid'))->leftJoin('game','game_record.game','=','game.game')->first();
            if(!Empty($data->endTime)){
                //對戰已結束
                //導向至紀錄頁面
                return Redirect::to('game/record/?rid='.$data->rid);
            }
            //指定的紀錄
            View::share('data',$data);
            //設定ajax取得網址
            $ajaxURL = URL::to('ajax/live/' . $data->rid);
            View::share('ajaxURL',$ajaxURL);
            //建立View
            return View::make('game.liveDisplay');
        }else{
            //列表
            $gameID = $arg;
            if($gameID!=""){
                //有指定遊戲
                //檢查遊戲是否存在，且未隱藏
                $count = DB::table('game')->where('game',$gameID)->where('hide',0)->count();
                if($count < 1){
                    //遊戲不存在
                    return Redirect::to('game/live');
                }
                //房間列表
                $roomList = DB::table('game_record')->where('game',$gameID)->whereNull('endTime')->orderBy('startTime','desc')->get();
            }else{
                //未指定遊戲
                //房間列表
                $roomList = DB::table('game_record')->whereNull('endTime')->orderBy('startTime','desc')->leftJoin('game','game_record.game','=','game.game')->get();
            }
            View::share('roomList',$roomList);
            //指定的遊戲ID
            View::share('gameID',$gameID);
            //遊戲清單
            $gameList = DB::table('game')->where('hide',0)->get();
            View::share('gameList',$gameList);
            //建立View
            return View::make('game.live');
        }
    }
    //對戰記錄
    public function record($arg="")
	{
        if($arg=="" && Input::has('rid') && func::isInt(Input::get('rid'))){
            //觀戰
            //檢查記錄是否存在
            $count = DB::table('game_record')->where('rid',Input::get('rid'))->count();
            if($count==0){
                //記錄不存在
                //設定訊息
                Session::put('jumpMsg','記錄不存在');
                Session::put('jumpURL',URL::to('game/live'));
                //導向至跳轉頁面
                return Redirect::to('jump');
            }
            //取得記錄資料
            $data = DB::table('game_record')->where('rid',Input::get('rid'))->leftJoin('game','game_record.game','=','game.game')->first();
            if(Empty($data->endTime)){
                //對戰未結束
                //導向至實況頁面
                return Redirect::to('game/live/?rid='.$data->rid);
            }
            //指定的紀錄
            View::share('data',$data);
            //建立View
            return View::make('game.recordDisplay');
        }else{
            //列表
            $gameID = $arg;
            if($gameID!=""){
                //有指定遊戲
                //檢查遊戲是否存在，且未隱藏
                $count = DB::table('game')->where('game',$gameID)->where('hide',0)->count();
                if($count < 1){
                    //遊戲不存在
                    return Redirect::to('game/record');
                }
                //房間列表
                $roomList = DB::table('game_record')->where('game',$gameID)->whereNotNull('endTime')->orderBy('startTime','desc')->get();
            }else{
                //未指定遊戲
                //房間列表
                $roomList = DB::table('game_record')->whereNotNull('endTime')->orderBy('startTime','desc')->leftJoin('game','game_record.game','=','game.game')->get();
            }
            View::share('roomList',$roomList);
            //指定的遊戲ID
            View::share('gameID',$gameID);
            //遊戲清單
            $gameList = DB::table('game')->where('hide',0)->get();
            View::share('gameList',$gameList);
            //建立View
            return View::make('game.record');
        }
    }
}