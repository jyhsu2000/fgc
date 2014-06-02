<?php

class NewsController extends BaseController {

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
    //news
    public function news($method="",$arg="")
    {
		//檢查是否有method
		if(Empty($method)){
			//顯示公告清單
			return $this->showList();
		}else if($method=="read" || $method=="edit"){
			//顯示公告內容或編輯公告
			//檢查是否有newsID名稱
			if(Empty($arg)){
				return Redirect::to('news');
			}else if(func::isInt($arg)){
                if($method=="read"){
                    //顯示公告內容
                    return $this->show($arg);
                }else if($method=="edit"){
                    //編輯公告
                    return $this->edit($arg);
                }
			}else{
				return Redirect::to('news');
			}
		}else if($method=="new"){
            //新增公告
            return $this->add();
        }else if($method=="delete"){
            //刪除公告
            return $this->delete($arg);
        }else if($method=="redirect"){
            //處理各種請求
            return $this->redirect();
        }else{
			return Redirect::to('news');
		}
        
    }
    //公告清單
    public function showList()
	{
		//每頁顯示數量
		$amount=10;
		//讀取公告清單
		$data = DB::table('bulletin')->orderBy('bid', 'desc')->leftJoin('game', 'bulletin.game', '=', 'game.game')->whereNull('bulletin.game')->orWhere('game.hide',0)->paginate($amount);
        View::share('data',$data);
        //建立View
		return View::make('news.list');
	}
    //公告內容
    public function show($newsID=0)
	{
		//檢查公告是否存在
		$count = DB::table('bulletin')->where('bid',$newsID)->count();
		if($count < 1){
			//設定訊息
			Session::put('jumpMsg','公告不存在');
			Session::put('jumpURL',URL::to('news'));
			//導向至跳轉頁面
			return Redirect::to('jump');
		}
        //讀取公告
		$data = DB::table('bulletin')->where('bid',$newsID)->leftJoin('game', 'bulletin.game', '=', 'game.game')->first();
        View::share('data',$data);
        //建立View
		return View::make('news.show');
	}
    //編輯公告
    public function edit($newsID=0)
	{
        //檢查是否有編輯公告權限
        if(!member::hasPerm("editNews") && !member::isGM()){
            return Redirect::to('news');
        }
		//檢查公告是否存在
		$count = DB::table('bulletin')->where('bid',$newsID)->count();
		if($count < 1){
			//設定訊息
			Session::put('jumpMsg','公告不存在');
			Session::put('jumpURL',URL::to('news'));
			//導向至跳轉頁面
			return Redirect::to('jump');
		}
        //讀取公告
		$data = DB::table('bulletin')->where('bid',$newsID)->first();
        View::share('data',$data);
        //檢查是否有該遊戲管理權限
        if(!member::hasPerm("editNews") && !($data->game!="" && member::isGM($data->game))){
            return Redirect::to('news');
        }
        //遊戲類型
        if(member::hasPerm("editNews")){
            $game = DB::table('game')->get();
        }else{
            $user = DB::table('user')->where('username',member::getEmail())->first();
            $game = DB::table('game')->join('gm','gm.game','=','game.game')->where('uid',$user->uid)->get();
        }
        View::share('game',$game);
        View::share('type',"edit");
        //建立View
		return View::make('news.edit');
	}
    //新增公告
    public function add()
	{
        //檢查是否有編輯公告權限
        if(!member::hasPerm("editNews") && !member::isGM()){
            return Redirect::to('news');
        }
        //遊戲類型
        if(member::hasPerm("editNews")){
            $game = DB::table('game')->get();
        }else{
            $user = DB::table('user')->where('username',member::getEmail())->first();
            $game = DB::table('game')->join('gm','gm.game','=','game.game')->where('uid',$user->uid)->get();
        }
        View::share('game',$game);
        View::share('type',"new");
        //建立View
		return View::make('news.edit');
	}
    //刪除公告
    public function delete($newsID=0)
	{
        //檢查是否有編輯公告權限
        if(!member::hasPerm("editNews") && !member::isGM()){
            return Redirect::to('news');
        }
		//檢查公告是否存在
		$count = DB::table('bulletin')->where('bid',$newsID)->count();
		if($count < 1){
			//設定訊息
			Session::put('jumpMsg','公告不存在');
			Session::put('jumpURL',URL::to('news'));
			//導向至跳轉頁面
			return Redirect::to('jump');
		}
        //讀取公告
		$data = DB::table('bulletin')->where('bid',$newsID)->first();
        //檢查是否有該遊戲管理權限
        if(!member::hasPerm("editNews") && !($data->game!="" && member::isGM($data->game))){
            return Redirect::to('news');
        }
        View::share('data',$data);
        //建立View
		return View::make('news.delete');
	}
    //處理各種請求
    public function redirect()
	{
        //檢查是否有編輯公告權限
        if(!member::hasPerm("editNews") && !(Input::get('game')!="" && member::isGM(Input::get('game')))){
            return Redirect::to('news');
        }
        //請求類型
        if(!Input::has('action')){
            //未指定動作類型
            //設定訊息
            Session::put('jumpMsg','未指定動作類型');
            Session::put('jumpURL',URL::to('news'));
            //導向至跳轉頁面
            return Redirect::to('jump');
        }else if(Input::get('action') == "edit"){
            //編輯公告
            //公告ID
            if(!Input::has('bid')){
                //沒有公告ID
                //設定訊息
                Session::put('jumpMsg','未指定公告ID');
                Session::put('jumpURL',URL::to('news'));
                //導向至跳轉頁面
                return Redirect::to('jump');
            }
            $newsID = Input::get('bid');
            //檢查公告是否存在
            $count = DB::table('bulletin')->where('bid',$newsID)->count();
            if($count < 1){
                //設定訊息
                Session::put('jumpMsg','公告不存在');
                Session::put('jumpURL',URL::to('news'));
                //導向至跳轉頁面
                return Redirect::to('jump');
            }
            //檢查是否有標題
            if(!Input::has('title') || strip_tags(Input::get('title'))==""){
                //設定訊息
                Session::put('jumpMsg','標題不得空白');
                //導向至跳轉頁面
                return Redirect::to('jumpBack');
            }
            //檢查是否有內容
            if(!Input::has('msg') || strip_tags(Input::get('msg'))==""){
                //設定訊息
                Session::put('jumpMsg','內容不得空白');
                //導向至跳轉頁面
                return Redirect::to('jumpBack');
            }
            //更新公告
            if(Input::has('game')){
                DB::table('bulletin')->where('bid',$newsID)->update(array(
                    'game'=>Input::get('game'),
                    'title'=>strip_tags(Input::get('title')),
                    'msg'=>func::closeHtmlTags(Input::get('msg')),
                ));
            }else{
                DB::table('bulletin')->where('bid',$newsID)->update(array(
                    'game'=>null,
                    'title'=>strip_tags(Input::get('title')),
                    'msg'=>func::closeHtmlTags(Input::get('msg')),
                ));
            }
            //讀取公告
            $data = DB::table('bulletin')->where('bid',$newsID)->first();
            //設定訊息
            Session::put('jumpMsg','編輯完成');
            Session::put('jumpURL',URL::to('news/read/'.$newsID));
            //導向至跳轉頁面
            return Redirect::to('jump');
        }else if(Input::get('action') == "new"){
            //新增公告
            //檢查是否有標題
            if(!Input::has('title') || strip_tags(Input::get('title'))==""){
                //設定訊息
                Session::put('jumpMsg','標題不得空白');
                //導向至跳轉頁面
                return Redirect::to('jumpBack');
            }
            //檢查是否有內容
            if(!Input::has('msg') || strip_tags(Input::get('msg'))==""){
                //設定訊息
                Session::put('jumpMsg','內容不得空白');
                //導向至跳轉頁面
                return Redirect::to('jumpBack');
            }
            //新增公告
            if(Input::has('game')){
                $newsID = DB::table('bulletin')->insertGetId(array(
                    'game'=>Input::get('game'),
                    'title'=>strip_tags(Input::get('title')),
                    'msg'=>func::closeHtmlTags(Input::get('msg')),
                    'date'=>date('Y-m-d H:i:s'),
                ));
            }else{
                $newsID = DB::table('bulletin')->insertGetId(array(
                    'title'=>strip_tags(Input::get('title')),
                    'msg'=>func::closeHtmlTags(Input::get('msg')),
                    'date'=>date('Y-m-d H:i:s'),
                ));
            }
            //讀取公告
            $data = DB::table('bulletin')->where('bid',$newsID)->first();
            //設定訊息
            Session::put('jumpMsg','新增完成');
            Session::put('jumpURL',URL::to('news/read/'.$newsID));
            //導向至跳轉頁面
            return Redirect::to('jump');
        }else if(Input::get('action') == "delete"){
            //刪除公告
            //公告ID
            if(!Input::has('bid')){
                //沒有公告ID
                //設定訊息
                Session::put('jumpMsg','未指定公告ID');
                Session::put('jumpURL',URL::to('news'));
                //導向至跳轉頁面
                return Redirect::to('jump');
            }
            $newsID = Input::get('bid');
            //檢查公告是否存在
            $count = DB::table('bulletin')->where('bid',$newsID)->count();
            if($count < 1){
                //設定訊息
                Session::put('jumpMsg','公告不存在');
                Session::put('jumpURL',URL::to('news'));
                //導向至跳轉頁面
                return Redirect::to('jump');
            }
            //刪除公告
            DB::table('bulletin')->where('bid',$newsID)->delete();
            //設定訊息
            Session::put('jumpMsg','已刪除');
            Session::put('jumpURL',URL::to('news'));
            //導向至跳轉頁面
            return Redirect::to('jump');
        }else{
            //未定義操作
            //設定訊息
            Session::put('jumpMsg','未定義操作');
            Session::put('jumpURL',URL::to('news'));
            //導向至跳轉頁面
            return Redirect::to('jump');
        }
	}
}