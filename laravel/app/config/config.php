<?php

return array(
    //基本設定
	'sitename' => '屯門遊樂局',         //網站名稱
    'allowRegister' => true,            //是否允許註冊
    'allowOAuth' => false,              //是否開啟OAuth（Facebook、Google登入）
    'forceSSL' => false,                //是否強制使用https連結
    
    //OAuth設定
    'fb_app_id' => "",
    'fb_app_secret' => "",
    'google_ClientId' => "",
    'google_ClientSecret' => "",
    'google_API_key' => "",
    
    //密碼加密參數
    /*
     * 以下請在網站上線運作前設定完成
     * 修改以下設定將導致目前所有帳號的密碼失效
    */
    'salt1' => '屯門遊樂局',
    'salt2' => 'KID'

);
