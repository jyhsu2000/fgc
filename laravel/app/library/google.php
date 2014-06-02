<?php

class google
{
    public static function Initialize()
    {
        // 引用 api client lib
        include_once "google-api-php-client/src/Google_Client.php";
        include_once "google-api-php-client/src/contrib/Google_AnalyticsService.php";
        global $google;
        
        $scriptUri = URL::to('/googleOAuth');

        $google = new Google_Client();
        $google->setAccessType('online'); // default: offline
        $google->setApplicationName('My Application name');
        $google->setClientId(Config::get('config.google_ClientId'));
        $google->setClientSecret(Config::get('config.google_ClientSecret'));
        $google->setRedirectUri($scriptUri);
        $google->setDeveloperKey(Config::get('config.google_API_key')); // API key
        $google->setScopes(array('https://www.googleapis.com/auth/userinfo.email','https://www.googleapis.com/auth/userinfo.profile'));
        
        $service = new Google_AnalyticsService($google);
        
    }
    public static function Auth()
    {
        global $google;
        if(Input::has('code')){
            $google->authenticate();
            Session::put('googleToken',$google->getAccessToken());
        }
    }
    public static function Check()
    {
        global $google,$googleProfile;
        if(Session::has('googleToken')){
            $googleToken = Session::get('googleToken');
            $google->setAccessToken($googleToken);
        }
        // 取得Google個人檔案
        if($google->getAccessToken()){
            //$json = file_get_contents('https://www.googleapis.com/oauth2/v2/userinfo?alt=json');
            $AccessToken=json_decode($google->getAccessToken(),true);
            $curl = curl_init('https://www.googleapis.com/oauth2/v2/userinfo?alt=json&access_token=' . $AccessToken['access_token']);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $json = curl_exec($curl);
            curl_close($curl); 
            
            $googleProfile = json_decode($json,true);
        }
    }
    public static function logout()
    {
        //登出
        //Google使用一般Session，無須額外登出動作
    }
}