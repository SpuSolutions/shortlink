<?php

use App\Link;

class LinkTest extends \PHPUnit_Framework_TestCase {

	public function testCreatingNewLinkAndAddingWordReturnsExpectedWord()
	{
		$word = "test1";
		$url = "http://www.pw4.net";
		$expireTime = 60;
		$expectedWord = "test1";

		$link = new Link();
		$link->setWord($word);
		$link->setUrl($url);
		$link->setExpireTime($expireTime);
		$this->assertEquals($expectedWord, $link->getWord());
	}

	public function testAddingUrlReturnsExpectedUrl()
	{
		$word = "test1";
		$url = "http://www.pw4.net";
		$expireTime = 60;
		$expectedUrl = "http://www.pw4.net";

		$link = new Link();
		$link->setWord($word);
		$link->setUrl($url);
		$link->setExpireTime($expireTime);
		$this->assertEquals($expectedUrl, $link->getUrl());
	}

	public function testAddingExpireTimeReturnsExpectedExpireTime()
	{
		$word = "test1";
		$url = "http://www.pw4.net";
		$expireTime = 60;
		$expectedExpireTime = 60;

		$link = new Link();
		$link->setWord($word);
		$link->setUrl($url);
		$link->setExpireTime($expireTime);
		$this->assertEquals($expectedExpireTime, $link->getExpireTime());
	}

	public function testAddingInvalidWordAndTestingValidityReturnsFalse()
	{
		$word = "12.$%!";
		$url = "http://www.pw4.net";
		$expireTime = 60;

		$link = new Link();
		$link->setWord($word);
		$link->setUrl($url);
		$link->setExpireTime($expireTime);
		$this->assertFalse($link->isValid());
	}

	public function testAddingValidWordAndTestingValidityReturnsTrue()
	{
		$word = "validWord";
		$url = "http://www.pw4.net";
		$expireTime = 60;

		$link = new Link();
		$link->setWord($word);
		$link->setUrl($url);
		$link->setExpireTime($expireTime);
		$this->assertTrue($link->isValid());
	}

	public function testAddingInvalidUrlAndTestingValidityReturnsFalse()
	{
		$word = "test";
		$url = "invalidurlwww.pw4";
		$expireTime = 60;

		$link = new Link();
		$link->setWord($word);
		$link->setUrl($url);
		$link->setExpireTime($expireTime);
		$this->assertFalse($link->isValid());
	}

	public function testAddingValidUrlAndTestingValidityReturnsTrue()
	{
		$word = "test";
		$url = "http://www.pw4.net";
		$expireTime = 60;

		$link = new Link();
		$link->setWord($word);
		$link->setUrl($url);
		$link->setExpireTime($expireTime);
		$this->assertTrue($link->isValid());
	}

	public function testAddingInvalidExpireTimeAndTestingValidityReturnsFalse()
	{
		$word = "test";
		$url = "http://www.pw4.net";
		$expireTime = "1.23";

		$link = new Link();
		$link->setWord($word);
		$link->setUrl($url);
		$link->setExpireTime($expireTime);
		$this->assertFalse($link->isValid());
	}

	public function testCreatingNewLinkWithoutAddingPropertiesAndTestingValidityReturnsFalse()
	{
		$link = new Link();
		$this->assertFalse($link->isValid());
	}

}