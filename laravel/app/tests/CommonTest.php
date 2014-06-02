<?php

class CommonTest extends TestCase {

    //訪問網站
	public function testVisit()
	{
		$this->client->request('GET', '/');
        $this->assertTrue($this->client->getResponse()->isOk());
	}
}