<?php

class APITest extends TestCase {

    var $data;
    
    public function setUp()
    {
        parent::setUp();
        global $data;
        $username = 'test1@fcu.edu.tw';
        $data = DB::table('user')->where('username',$username)->first();
        //登入Test1帳號
        Session::put('username',$username);
        Session::put('password',$data->password);
    }
    //刷新並取得Token
	public function testGetToken()
	{
        //正確請求
        global $data;
        $arr = array(
            'username' => $data->username,
            'password' => 'test1',
            'gameID' => 'fgcChess'
        );
		$response = $this->callSecure('POST', '/api/getToken', $arr );
        $result = json_decode($response->getContent());
        $data = DB::table('user')->where('username',$data->username)->first();
        $exp = array(
            "result" => true,
            "username" => $data->username,
            "gameID" => "fgcChess",
            "id" => "test1",
            "token" => $data->token,
            "tokenDeadline" => strtotime($data->tokenDeadline)
        );
        $this->assertEquals((array)$result,$exp);
	}
	public function testGetTokenInvalidRequest()
	{
        //Invalid Request
        global $data;
        $arr = array(
        );
		$response = $this->callSecure('POST', '/api/getToken', $arr );
        $result = json_decode($response->getContent());
        $exp = array(
            "result" => false,
            "error" => "Invalid Request"
        );
        $this->assertEquals((array)$result,$exp);
	}
	public function testGetTokenLoginFailed()
	{
        //Login Failed
        global $data;
        $arr = array(
            'username' => $data->username,
            'password' => 'asdasdasdasd',
        );
		$response = $this->callSecure('POST', '/api/getToken', $arr );
        $result = json_decode($response->getContent());
        $exp = array(
            "result" => false,
            "error" => "Login Failed"
        );
        $this->assertEquals((array)$result,$exp);
	}
	public function testGetTokenEmailUnverified()
	{
        //Email Unverified
        global $data;
        $arr = array(
            'username' => $data->username,
            'password' => 'test1',
        );
        //暫時改為未驗證
        DB::table('user')->where('username',$data->username)->update(array('group'=>'unverified'));
		$response = $this->callSecure('POST', '/api/getToken', $arr );
        //改回一般會員
        DB::table('user')->where('username',$data->username)->update(array('group'=>'user'));
        $result = json_decode($response->getContent());
        $exp = array(
            "result" => false,
            "error" => "Email Unverified"
        );
        $this->assertEquals((array)$result,$exp);
	}
	public function testGetTokenRequiregameID()
	{
        //Require gameID
        global $data;
        $arr = array(
            'username' => $data->username,
            'password' => 'test1',
        );
		$response = $this->callSecure('POST', '/api/getToken', $arr );
        $result = json_decode($response->getContent());
        $exp = array(
            "result" => false,
            "error" => "Require gameID"
        );
        $this->assertEquals((array)$result,$exp);
	}
	public function testGetTokenInvalidgameID()
	{
        //Invalid gameID
        global $data;
        $arr = array(
            'username' => $data->username,
            'password' => 'test1',
            'gameID' => 'The_Game_Which_Is_Not_Exist'
        );
		$response = $this->callSecure('POST', '/api/getToken', $arr );
        $result = json_decode($response->getContent());
        $exp = array(
            "result" => false,
            "error" => "Invalid gameID"
        );
        $this->assertEquals((array)$result,$exp);
	}
	public function testGetTokenNoID()
	{
        //No ID
        global $data;
        $arr = array(
            'username' => $data->username,
            'password' => 'test1',
            'gameID' => 'cayChess'
        );
		$response = $this->callSecure('POST', '/api/getToken', $arr );
        $result = json_decode($response->getContent());
        $exp = array(
            "result" => false,
            "error" => "No ID"
        );
        $this->assertEquals((array)$result,$exp);
	}
    //檢查Token
    public function testCheckToken()
    {
        //正確請求
        global $data;
        $arr = array(
            'username' => $data->username,
            'token' => $data->token
        );
		$response = $this->callSecure('POST', '/api/checkToken', $arr );
        $result = json_decode($response->getContent());
        $exp = array(
            "result" => true,
            "username" => $data->username,
            "token" => $data->token
        );
        $this->assertEquals((array)$result,$exp);
    }
    public function testCheckTokenInvalidRequest()
    {
        //Invalid Request
        global $data;
        $arr = array(
        );
		$response = $this->callSecure('POST', '/api/checkToken', $arr );
        $result = json_decode($response->getContent());
        $exp = array(
            "result" => false,
            "error" => "Invalid Request"
        );
        $this->assertEquals((array)$result,$exp);
    }
    public function testCheckTokenInvalidusername()
    {
        //Invalid username
        global $data;
        $arr = array(
            'username' => "The_Username_Which_Is_Not_Exist",
            "token" => $data->token
        );
		$response = $this->callSecure('POST', '/api/checkToken', $arr );
        $result = json_decode($response->getContent());
        $exp = array(
            "result" => false,
            "error" => "Invalid username"
        );
        $this->assertEquals((array)$result,$exp);
    }
    public function testCheckTokenInvalidtoken()
    {
        //Invalid token
        global $data;
        $arr = array(
            'username' => $data->username,
            "token" => "This_IS_A_Invalid_Token"
        );
		$response = $this->callSecure('POST', '/api/checkToken', $arr );
        $result = json_decode($response->getContent());
        $exp = array(
            "result" => false,
            "error" => "Invalid token"
        );
        $this->assertEquals((array)$result,$exp);
    }
    public function testCheckTokenTokenexpired()
    {
        //Token expired
        global $data;
        $arr = array(
            'username' => $data->username,
            "token" => $data->token
        );
        //暫時改為過期
        $tmp = $data->tokenDeadline;
        DB::table('user')->where('username',$data->username)->update(array('tokenDeadline'=>'0'));
		$response = $this->callSecure('POST', '/api/checkToken', $arr );
        //改回原本的期限
        DB::table('user')->where('username',$data->username)->update(array('tokenDeadline'=>$tmp));
        $result = json_decode($response->getContent());
        $exp = array(
            "result" => false,
            "error" => "Token expired"
        );
        $this->assertEquals((array)$result,$exp);
    }
    //取得角色ID
    public function testGetID()
    {
        //正確請求
        global $data;
        $arr = array(
            'username' => $data->username,
            'gameID' => 'fgcChess'
        );
		$response = $this->callSecure('POST', '/api/getID', $arr );
        $result = json_decode($response->getContent());
        $exp = array(
            "result" => true,
            "username" => $data->username,
            "gameID" => "fgcChess",
            "id" => "test1"
        );
        $this->assertEquals((array)$result,$exp);
    }
    public function testGetIDInvalidRequest()
    {
        //Invalid Request
        global $data;
        $arr = array(
        );
		$response = $this->callSecure('POST', '/api/getID', $arr );
        $result = json_decode($response->getContent());
        $exp = array(
            "result" => false,
            "error" => "Invalid Request"
        );
        $this->assertEquals((array)$result,$exp);
    }
    public function testGetIDInvalidusername()
    {
        //Invalid username
        global $data;
        $arr = array(
            'username' => "The_Username_Which_Is_Not_Exist",
            'gameID' => 'fgcChess'
        );
		$response = $this->callSecure('POST', '/api/getID', $arr );
        $result = json_decode($response->getContent());
        $exp = array(
            "result" => false,
            "error" => "Invalid username"
        );
        $this->assertEquals((array)$result,$exp);
    }
    public function testGetIDInvalidgameID()
    {
        //Invalid gameID
        global $data;
        $arr = array(
            'username' => $data->username,
            'gameID' => 'The_Game_Which_Is_Not_Exist'
        );
		$response = $this->callSecure('POST', '/api/getID', $arr );
        $result = json_decode($response->getContent());
        $exp = array(
            "result" => false,
            "error" => "Invalid gameID"
        );
        $this->assertEquals((array)$result,$exp);
    }
    public function testGetIDNoID()
    {
        //No ID
        global $data;
        $arr = array(
            'username' => $data->username,
            'gameID' => 'cayChess'
        );
		$response = $this->callSecure('POST', '/api/getID', $arr );
        $result = json_decode($response->getContent());
        $exp = array(
            "result" => false,
            "error" => "No ID"
        );
        $this->assertEquals((array)$result,$exp);
    }
}