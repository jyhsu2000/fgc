<!doctype html>
<?php
    //設定時區
    date_default_timezone_set('Asia/Taipei');
    //資料庫設定
    $arr = include "laravel/app/config/database.php";
    $mysql = $arr['connections']['mysql'];
    //設定
    $config = include "laravel/app/config/config.php";
    //步驟
    if(isset($_GET['step']) && !Empty($_GET['step']) && is_numeric($_GET['step'])){
        $step = $_GET['step'];
    }else{
        header("Location: {$_SERVER['PHP_SELF']}?step=1");
    }
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>安裝 - 屯門遊樂局</title>
        <script src="https://code.jquery.com/jquery.js"></script>
        <script src="bootstrap/js/bootstrap.js"></script>
        <link media="all" type="text/css" rel="stylesheet" href="bootstrap/css/bootstrap.css">
        <link media="all" type="text/css" rel="stylesheet" href="font-awesome/css/font-awesome.css">
        <link media="all" type="text/css" rel="stylesheet" href="jQuery-Validation-Engine-master/css/validationEngine.jquery.css">
        <script src="jQuery-Validation-Engine-master/js/jquery.validationEngine.js"></script>
        <script src="jQuery-Validation-Engine-master/js/languages/jquery.validationEngine-zh_TW.js"></script>
        <style type="text/css">
        html {
            overflow-y: scroll; 
        }
        html, body {
            height: 100%;
        }
        body{
            background-color:#fafafa;
            text-align:center;
            font-family: Microsoft JhengHei, verdana, Times New Roman, 新細明體;
        }
        </style>
        <script type="text/javascript">
        //表單驗證
        jQuery(document).ready(function(){
            // binds form submission and fields to the validation engine
            jQuery("#formID").validationEngine();
        });
        </script>
    </head>
    <body>
        <div class="container">
            <img src="/resource/pic/banner.jpg" width="1000px" height="192px" />
        </div>
        <div class="container" style="min-height:60%;margin-bottom:60px;">
            <div class="row-fluid">
                <div class="offset2 span8">
                    <div class="alert alert-info text-center">
                        <div class="text-center"><h2>屯門遊樂局</h2></div>
                        <?php
                        //連結資料庫
                        try{
                            $conn = @mysqli_connect($mysql['host'],$mysql['username'],$mysql['password'],$mysql['database']);
                            @mysqli_query("SET NAMES utf8");
                            $sql = "SELECT * FROM `test`;";
                            $result = @mysqli_query($conn ,$sql);
                            //判斷錯誤
                            if (mysqli_connect_errno()) {
                                throw new RuntimeException("無法連結資料庫:" . mysqli_connect_errno() . "<br />" . mysqli_connect_error());
                            }else if(!$result){
                                throw new RuntimeException("未將 init.sql 匯入資料庫");
                            }
                            //順利連上資料庫
                            //計算會員數量
                            $sql = "SELECT * FROM `user`;";
                            $result = @mysqli_query($conn ,$sql);
                            $count = mysqli_num_rows($result);
                            if($count > 0){
                                throw new RuntimeException("設定完成，請刪除此檔案");
                            }
                            //依步驟決定
                            if($step==1){
                                $msg= <<<m
<form id="formID" method="post" action="{$_SERVER['PHP_SELF']}?step=2">
    <fieldset>
        <legend><h3>設定系統管理員帳號</h3></legend>
        <p>
            <div class="input-prepend">
                <span class="add-on"><i class="fa fa-user"></i></span>
                <input type="text" id="username" name="username" placeholder="請輸入信箱..." required class="validate[required,custom[email]] input-xlarge">
            </div>
        </p>
        <p>
            <div class="input-prepend">
                <span class="add-on"><i class="fa fa-lock"></i></span>
                <input type="password" id="password" name="password" placeholder="請輸入密碼..." required class="validate[required] input-xlarge">
            </div>
        </p>
        <p>
            <div class="input-prepend">
                <span class="add-on"><i class="fa fa-lock"></i></span>
                <input type="password" id="password2" name="password2" placeholder="請再輸入一次密碼..." required class="validate[required,equals[password]]] input-xlarge">
            </div>
        </p>
        <p>
            <button type="submit" class="btn btn-primary">確定</button>
        </p>
    </fieldset>
</form>
m;
                                echo $msg;
                            }else if($step==2){
                                $error = false;
                                if(Empty($_POST['username']) || Empty($_POST['password']) || Empty($_POST['password2'])){
                                    //檢查帳號與密碼皆有輸入
                                    $error = true;
                                    $error_msg = "請輸入信箱及密碼";
                                }else if($_POST['password'] != $_POST['password2']){
                                    //檢查密碼是否相同
                                    $error = true;
                                    $error_msg = "兩次密碼輸入不相同";
                                }else if($_POST['username'] == $_POST['password']){
                                    //檢查帳號與密碼相重
                                    $error = true;
                                    $error_msg = "密碼不得與帳號相同";
                                }
                                if($error){
                                    //發生錯誤
                                    echo "<div class=\"alert alert-error\">";
                                    echo $error_msg . "<br />";
                                    echo "<a class=\"btn\" href=\"{$_SERVER['PHP_SELF']}?step=1\">返回</a>";
                                    echo "</div>";
                                }else{
                                    //未發生錯誤
                                    $username = str_replace("'","",$_POST['username']);
                                    $password = str_replace("'","",$_POST['password']);
                                    $salt1 = $config['salt1'];
                                    $salt2 = $config['salt2'];
                                    $pw_hash = md5($salt2.md5(md5($password).$salt1));
                                    $tmpstr = explode("@",$username);
                                    $nickname = $tmpstr[0];
                                    //更新資料
                                    $sql = "INSERT INTO `user` (`username`,`password`,`nickname`,`loginType`,`group`) VALUES('{$username}','{$pw_hash}','{$nickname}','local','admin')";
                                    $result = @mysqli_query($conn ,$sql);
                                    //顯示訊息
                                    echo "<div class=\"alert alert-block\">";
                                    echo "系統管理員設定完成，請刪除此檔案";
                                    echo "</div>";
                                }
                            }else{
                                header("Location: {$_SERVER['PHP_SELF']}?step=1");
                            }
                        }catch (Exception $e){
                            echo "<div class=\"alert alert-error\">";
                            echo $e->getmessage();
                            echo "</div>";
                        }finally{
                            //關閉連結
                            @mysqli_close($conn);
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div id="footer">
                <hr />
            <div class="container">
                <div class="row-fluid">
                    <div class="span6">
                        <p align="left" style="color:gray">
                        Powered by <span class="label">TomTiger</span><br />
                        © 2014 <span class="label">屯門遊樂局</span>
                        </p>
                    </div>
                    <div class="span6">
                        <p align="right" style="color:gray">
                        <?=date('Y-m-d H:i:s') ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
