<?php

class LibMemberTest extends TestCase {

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
    //檢查是否登入中
	public function testCheck()
	{
        global $data;
		$this->assertTrue(member::check());
	}
    //取得信箱
	public function testGetEmail()
	{
        global $data;
		$this->assertEquals(member::getEmail(),$data->username);
	}
    //取得登入方式
	public function testGetType()
	{
        global $data;
		$this->assertEquals(member::getType(),$data->loginType);
	}
    //取得暱稱
    public function testGetName()
	{
        global $data;
		$this->assertEquals(member::getName(),$data->nickname);
	}
    //取得ID
	public function testGetID()
	{
        global $data;
        $id = DB::table('avatar')->where('username',$data->username)->where('game','fgcChess')->first()->id;
		$this->assertEquals(member::getID('fgcChess'),$id);
	}
    //取得群組
	public function testGetGroup()
	{
        global $data;
		$this->assertEquals(member::getGroup(),$data->group);
	}
    //取得群組名稱
	public function testGetGroupName()
	{
        global $data;
        $groupName = DB::table('group')->where('group',$data->group)->first()->groupName;
		$this->assertEquals(member::getGroupName(),$groupName);
	}
    //檢查是否擁有特定權限
	public function testHasPerm()
	{
        global $data;
        $hasPermEditGame = DB::table('group')->where('group',$data->group)->first()->editGame;
		$this->assertEquals(member::hasPerm('editGame'),$hasPermEditGame);
	}
    //檢查是否為特定遊戲的管理人員
	public function testIsGM()
	{
        global $data;
        $isGM = (DB::table('gm')->where('game','fgcChess')->where('uid',$data->uid)->count()>0) ? true : false;
		$this->assertEquals(member::isGM('fgcChess'),$isGM);
	}
    //檢查帳號是否存在
	public function testIsExist()
	{
        global $data;
		$this->assertTrue(member::isExist($data->username));
	}

}