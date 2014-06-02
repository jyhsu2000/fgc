<?php

class fb
{
    public static function Initialize()
    {
        include_once "facebook-php-sdk/src/facebook.php"; // 引用 sdk lib
        global $config,$facebook,$fbUserID,$fbProfile;
        
        $app_id = Config::get('config.fb_app_id');
        $app_secret = Config::get('config.fb_app_secret');
        
        $config = array(
            'appId' => $app_id,  // app id
            'secret' => $app_secret, // app secret
            'fileUpload' => false, // 是否可以透過 app 上傳檔案（EX. 頭像,圖片... etc）
            'allowSignedRequest' => false, // 非 canvas 的 app 設定 false
        );
        $facebook = new Facebook($config);
        $fbUserID = $facebook->getUser();   // 取得使用者ID
        // 取得FB個人檔案
        if($fbUserID) {
            $fbProfile = $facebook->api("/me","GET");
        }
    }
    public static function logout()
    {
        global $facebook,$fbUserID;
        //取消授權
        //$facebook->api("/me/permissions","DELETE");
        
        //登出
        $facebook->destroySession();
        $facebook->setAccessToken('');
    }
}