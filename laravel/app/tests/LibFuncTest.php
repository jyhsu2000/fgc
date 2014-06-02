<?php

class LibFuncTest extends TestCase {

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
    //密碼加密法
	public function testHash()
	{
        global $data;
		$this->assertEquals(func::Hash('test1'),$data->password);
	}
    //檢查是否符合Email格式
    public function testIsEmail()
	{
		$this->assertTrue((bool)func::isEmail('test1@fcu.edu.tw'));
		$this->assertFalse((bool)func::isEmail('test1#fcu.edu.tw'));
	}
    //特定兩個字串之間的字串
    public function testCatchStr()
	{
        $str = "ABCDE{1234567890}FGHIJK";
        $result = func::CatchStr($str,"{","}");
		$this->assertEquals($result[0],"1234567890");
		$this->assertEquals($result[1],"ABCDE");
		$this->assertEquals($result[2],"FGHIJK");
        
        $result = func::CatchStr($str,"{","0}F");
		$this->assertEquals($result[0],"123456789");
		$this->assertEquals($result[1],"ABCDE");
		$this->assertEquals($result[2],"GHIJK");
        
        $result = func::CatchStr($str,"E{1","}");
		$this->assertEquals($result[0],"234567890");
		$this->assertEquals($result[1],"ABCD");
		$this->assertEquals($result[2],"FGHIJK");
	}
    //檢查是否為整數
    public function testIsInt(){
        $this->assertTrue(func::isInt("456789"));
        $this->assertFalse(func::isInt("456.789"));
        $this->assertFalse(func::isInt("45w789"));
    }
    //限制輸入行數
    public function testKeepXLines(){
        $str1 = "line1\nline2\nline3\nline4\nline5";
        $str2 = "line1\nline2\nline3";
        $this->assertEquals(func::keepXLines($str1,3),$str2);
    }
    //HTML成對檢查
    public function testCloseHtmlTags() {
        $str1 = "<b><i>test";
        $str2 = "<b><i>test</i></b>";
        $this->assertEquals(func::closeHtmlTags($str1),$str2);
    }
}