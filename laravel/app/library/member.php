<?php

class member
{
    //檢查是否登入
    public static function check()
    {
        //cookie login
        if((!Session::has('username') || !Session::has('password')) && Cookie::get('username')!="" && Cookie::get('password')!=""){
            $count = DB::table('user')->where('username',Cookie::get('username'))->count();
            if($count >= 1){
                $data = DB::table('user')->where('username',Cookie::get('username'))->first();
                if(Cookie::get('password') == md5($data->password)){
                    Session::put('username',Cookie::get('username'));
                    Session::put('password',$data->password);
                }
            }
        }
        //local
        if(Session::has('username') && Session::has('password')){
            $count = DB::table('user')->where('username',Session::get('username'))->where('password',Session::get('password'))->count();
            if($count >= 1){
                return true;
            }
        }
        //檢查是否開啟OAuth
        if(Config::get('config.allowOAuth')){
            //facebook
            global $fbUserID;
            if($fbUserID){
                return true;
            }
            //google
            global $googleProfile;
            //若google oauth資料出錯（OAuth逾時，但本地還沒）
            if(!Empty($googleProfile) && !isset($googleProfile['id'])){
                $googleProfile = null;
                return false;
            }
            if($googleProfile['id']){
                return true;
            }
        }
        return false;
    }
    //取得信箱
    public static function getEmail()
    {
        if(member::check()==false){
            return null;
        }
        if(member::getType() == "local"){
            //local
            return Session::get('username');
        }else if(member::getType() == "facebook"){
            //facebook
            global $fbProfile;
            return $fbProfile['email'];
        }else if(member::getType() == "google"){
            //google
            global $googleProfile;
            return $googleProfile['email'];
        }
        return null;
    }
    //取得登入方式
    public static function getType($email="")
    {
        //若有輸入Email，且該信箱確實存在，直接從資料庫調資料
        if(member::isExist($email)){
            return DB::table('user')->where('username',$email)->first()->loginType;
        }
        //若無資料，開始檢查目前帳號
        if(member::check()==false){
            return null;
        }
        //local
        if(Session::has('username') && Session::has('password')){
            $count = DB::table('user')->where('username',Session::get('username'))->where('password',Session::get('password'))->count();
            if($count >= 1){
                return "local";
            }
        }
        //facebook
        global $fbUserID;
        if($fbUserID){
            return "facebook";
        }
        //google
        global $googleProfile;
        if($googleProfile['id']){
            return "google";
        } 
        return null;
    }
    //取得暱稱
    public static function getName($email="")
    {
        //Email沒填寫或無效，預設為當前用戶
        if($email==null || Empty($email) || !member::isExist($email)){
            if(member::check()){
                $email = member::getEmail();
            }else{
                return null;
            }
        }
        return DB::table('user')->where('username',$email)->first()->nickname;
    }
    //取得ID
    public static function getID($gameID,$email="")
    {
        //Email沒填寫或無效，預設為當前用戶
        if($email==null || Empty($email) || !member::isExist($email)){
            if(member::check()){
                $email = member::getEmail();
            }else{
                return null;
            }
        }
        //檢查是否擁有ID
        $count = DB::table('avatar')->where('username',$email)->where('game',$gameID)->count();
        if($count>0){
            return DB::table('avatar')->where('username',$email)->where('game',$gameID)->first()->id;
        }else{
            return null;
        }
    }
    //取得群組
    public static function getGroup($email="")
    {
        //Email沒填寫或無效，預設為當前用戶
        if($email==null || Empty($email) || !member::isExist($email)){
            if(member::check()){
                $email = member::getEmail();
            }else{
                return "guest";
            }
        }
        return DB::table('user')->where('username',$email)->first()->group;
    }
    //取得群組名稱
    public static function getGroupName($email="")
    {
        return DB::table('group')->where('group',member::getGroup($email))->first()->groupName;
    }
    //取得大頭貼路徑
    public static function getImage($size,$email="")
    {
        //若有輸入Email，且該信箱確實存在，直接從資料庫調資料
        if(member::isExist($email)){
            if(member::getType($email)=="local"){
                //本地帳號，使用gravatar
                $default = "mm";
                return "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "?d=" . urlencode( $default ) . "&s=" . $size;
            }else{
                //非本地帳號
                //暫不支援
                return null;
            }
        }
        if(member::check()==false){
            return null;
        }
        if(member::getType() == "local"){
            //local，使用gravatar
            $default = "mm";
            return "https://www.gravatar.com/avatar/" . md5( strtolower( trim( member::getEmail() ) ) ) . "?d=" . urlencode( $default ) . "&s=" . $size;
        }else if(member::getType() == "facebook"){
            //facebook
            global $fbProfile;
            return "https://graph.facebook.com/{$fbProfile['id']}/picture?width={$size}&height={$size}";
        }else if(member::getType() == "google"){
            //google
            global $googleProfile;
            
            $curl = curl_init("https://picasaweb.google.com/data/entry/api/user/{$googleProfile['id']}");
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($curl);
            curl_close($curl);
            
            $url = func::CatchStr($result,"<gphoto:thumbnail>","</gphoto:thumbnail>");
            $url = str_replace("/s64","/s{$size}",$url[0]);
            return $url;
        }
        return null;
    }
    //檢查是否擁有特定權限
    public static function hasPerm($perm,$email="")
    {
        //必須輸入權限
        if($perm==null || Empty($perm)){
            return false;
        }
        //Email沒填寫或無效，預設為當前用戶
        if($email==null || Empty($email) || !member::isExist($email)){
            if(member::check()){
                $email = member::getEmail();
                $group = member::getGroup($email);
            }else{
                $group = "guest";
            }
        }
        if(DB::table('group')->where('group',$group)->first()->$perm == 1){
            return true;
        }else{
            return false;
        }
    }
    //檢查是否為特定遊戲的管理人員
    public static function isGM($game="",$email="")
    {
        //Email沒填寫或無效，預設為當前用戶
        if($email==null || Empty($email) || !member::isExist($email)){
            if(member::check()){
                $email = member::getEmail();
            }else{
                return false;
            }
        }
        //系統管理員預設為所有遊戲的管理員
        if(member::hasPerm("editGame")){
            return true;
        }
        //檢查是否為該遊戲管理員
        $data = DB::table('user')->where('username',$email)->first();
        //若沒指定遊戲，檢查是否為任何遊戲管理員
        if($game==""){
            $count = DB::table('gm')->where('uid',$data->uid)->count();
        }else{
            $count = DB::table('gm')->where('game',$game)->where('uid',$data->uid)->count();
        }
        if($count >= 1){
            return true;
        }else{
            return false;
        }
    }
    //檢查帳號是否存在
    public static function isExist($email="")
    {
        //必須輸入Email
        if($email==null || Empty($email)){
            return false;
        }
        $count = DB::table('user')->where('username',$email)->count();
        if($count >= 1){
            return true;
        }
        return false;
    }
}