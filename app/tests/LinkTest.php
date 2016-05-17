<?php

use App\Link\Link;

class LinkTest extends \PHPUnit_Framework_TestCase {

	public function testCreatingNewLinkAndAddingWordReturnsExpectedWord()
	{
		$word = "testWord";
		$url = "http://www.google.com";
		$expireTime = 60;
		$expectedWord = "testWord";

		$link = new Link();
		$link->setWord($word);
		$link->setUrl($url);
		$link->setExpireTime($expireTime);
		$this->assertEquals($expectedWord, $link->getWord());
	}

	public function testAddingUrlReturnsExpectedUrl()
	{
		$word = "testWord";
		$url = "http://www.google.com";
		$expireTime = 60;
		$expectedUrl = "http://www.google.com";

		$link = new Link();
		$link->setWord($word);
		$link->setUrl($url);
		$link->setExpireTime($expireTime);
		$this->assertEquals($expectedUrl, $link->getUrl());
	}

	public function testAddingExpireTimeReturnsExpectedExpireTime()
	{
		$word = "testWord";
		$url = "http://www.google.com";
		$expireTime = 60;
		$expectedExpireTime = 60;

		$link = new Link();
		$link->setWord($word);
		$link->setUrl($url);
		$link->setExpireTime($expireTime);
		$this->assertEquals($expectedExpireTime, $link->getExpireTime());
	}

	public function testAddingCreatedTimeReturnsExpectedCreatedTime()
	{
		$word = "testWord";
		$url = "http://www.google.com";
		$expireTime = 60;
		$created = 1463504590;
		$expectedCreated = 1463504590;

		$link = new Link();
		$link->setWord($word);
		$link->setUrl($url);
		$link->setExpireTime($expireTime);
		$link->setCreated($created);
		$this->assertEquals($expectedCreated, $link->getCreated());
	}

	public function testAddingTruePasswordProtectedReturnsExpectedPasswordProtected()
	{
		$word = "testWord";
		$url = "http://www.google.com";
		$expireTime = 60;
		$created = 1463504590;
		$passwordProtected = true;
		$expectedPasswordProtected = true;

		$link = new Link();
		$link->setWord($word);
		$link->setUrl($url);
		$link->setExpireTime($expireTime);
		$link->setCreated($created);
		$link->setPasswordProtected($passwordProtected);
		$this->assertEquals($expectedPasswordProtected, $link->getPasswordProtected());
	}

	public function testAddingFalsePasswordProtectedReturnsExpectedPasswordProtected()
	{
		$word = "testWord";
		$url = "http://www.google.com";
		$expireTime = 60;
		$created = 1463504590;
		$passwordProtected = false;
		$expectedPasswordProtected = false;

		$link = new Link();
		$link->setWord($word);
		$link->setUrl($url);
		$link->setExpireTime($expireTime);
		$link->setCreated($created);
		$link->setPasswordProtected($passwordProtected);
		$this->assertEquals($expectedPasswordProtected, $link->getPasswordProtected());
	}

	public function addAddingInvalidWordAndTestingValidityReturnsFalseDataProvider()
	{
		return [
        	['12.$%!'],
        	['1'],
        	['1a'],
        	['a#'],
        	['A()'],        	
        	['a.'],        	
        ];
	}

	/**
     * @dataProvider addAddingInvalidWordAndTestingValidityReturnsFalseDataProvider
     */
	public function testAddingInvalidWordAndTestingValidityReturnsFalse($word)
	{
		$url = "http://www.google.com";
		$expireTime = 60;

		$link = new Link();
		$link->setWord($word);
		$link->setUrl($url);
		$link->setExpireTime($expireTime);
		$this->assertFalse($link->isValid());
	}

	public function addAddingValidWordAndTestingValidityReturnsTrueDataProvider()
	{
		return [
        	['http://www.google.com'],
        	['https://www.yahoo.com'],
        	['ftp://google.com'],
        ];
	}

	/**
     * @dataProvider addAddingValidWordAndTestingValidityReturnsTrueDataProvider
     */
	public function testAddingValidWordAndTestingValidityReturnsTrue($url)
	{
		$word = "validWord";
		$expireTime = 60;

		$link = new Link();
		$link->setWord($word);
		$link->setUrl($url);
		$link->setExpireTime($expireTime);
		$this->assertTrue($link->isValid());
	}

	public function addAddingInvalidUrlAndTestingValidityReturnsFalseDataProvider()
	{
		return [
        	['www.google.com'],
        	['http:www.google.com'],
        	['http:/www.google.com'],
        	['http:/wwwgoogle'],
        ];
	}

	/**
     * @dataProvider addAddingInvalidUrlAndTestingValidityReturnsFalseDataProvider
     */
	public function testAddingInvalidUrlAndTestingValidityReturnsFalse($url)
	{
		$word = "test";
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
		$url = "http://www.google.com";
		$expireTime = 60;

		$link = new Link();
		$link->setWord($word);
		$link->setUrl($url);
		$link->setExpireTime($expireTime);
		$this->assertTrue($link->isValid());
	}

	public function addAddingInvalidExpireTimeAndTestingValidityReturnsFalseDataProvider()
	{
		return [
        	['1.23'],
        	['123456789'],
        	[-123],
        	[9999999],
        	['-12'],
        ];
	}

	/**
     * @dataProvider addAddingInvalidExpireTimeAndTestingValidityReturnsFalseDataProvider
     */
	public function testAddingInvalidExpireTimeAndTestingValidityReturnsFalse($expireTime)
	{
		$word = "test";
		$url = "http://www.google.com";

		$link = new Link();
		$link->setWord($word);
		$link->setUrl($url);
		$link->setExpireTime($expireTime);
		$this->assertFalse($link->isValid());
	}

	public function testAddingValidExpireTimeAndTestingValidityReturnsTrue()
	{
		$word = "test";
		$url = "http://www.google.com";
		$expireTime = "60";

		$link = new Link();
		$link->setWord($word);
		$link->setUrl($url);
		$link->setExpireTime($expireTime);
		$this->assertFalse($link->isValid());
	}

	public function addAddingInvalidCreatedTimeAndTestingValidityReturnsFalseDataProvider() {
        return [
        	['1212d12f12f'],
        	['123456789'],
        	[123456789],
        	[12345678910]
        ];
    }

	/**
     * @dataProvider addAddingInvalidCreatedTimeAndTestingValidityReturnsFalseDataProvider
     */
	public function testAddingInvalidCreatedTimeAndTestingValidityReturnsFalse($created)
	{
		$word = "test";
		$url = "http://www.google.com";
		$expireTime = 60;

		$link = new Link();
		$link->setWord($word);
		$link->setUrl($url);
		$link->setExpireTime($expireTime);
		$link->setCreated($created);
		$this->assertFalse($link->isValid());
	}

	public function testAddingValidCreatedTimeAndTestingValidityReturnsTrue()
	{
		$word = "test";
		$url = "http://www.google.com";
		$expireTime = 60;
		$created = 1463504590;

		$link = new Link();
		$link->setWord($word);
		$link->setUrl($url);
		$link->setExpireTime($expireTime);
		$link->setCreated($created);
		$this->assertTrue($link->isValid());
	}

	public function testAddingInvalidPasswordProctedAndTestingValidityReturnsFalse()
	{
		$word = "test";
		$url = "http://www.google.com";
		$expireTime = 60;
		$passwordProtected = "false";

		$link = new Link();
		$link->setWord($word);
		$link->setUrl($url);
		$link->setExpireTime($expireTime);
		$link->setPasswordProtected($passwordProtected);
		$this->assertFalse($link->isValid());
	}

	public function testAddingValidPasswordProctedAndTestingValidityReturnsTrue()
	{
		$word = "test";
		$url = "http://www.google.com";
		$expireTime = 60;
		$passwordProtected = false;

		$link = new Link();
		$link->setWord($word);
		$link->setUrl($url);
		$link->setExpireTime($expireTime);
		$link->setPasswordProtected($passwordProtected);
		$this->assertTrue($link->isValid());
	}

	public function testCreatingNewLinkWithoutAddingPropertiesAndTestingValidityReturnsFalse()
	{
		$link = new Link();
		$this->assertFalse($link->isValid());
	}

	public function testCreatingNewLinkSetsNonEmptyCreatedTime()
	{
		$link = new Link();
		$this->assertNotEmpty($link->getCreated());
	}

	public function testCreatingLinkAndCheckingHasExpiredReturnsFalse()
	{
		$word = "test";
		$url = "http://www.google.com";
		$expireTime = 60;

		$link = new Link();
		$link->setWord($word);
		$link->setUrl($url);
		$link->setExpireTime($expireTime);
		$this->assertFalse($link->hasExpired());
	}

	public function testCreatingLinkWithCreatedOlderThanExpireTimeAndCheckingHasExpiredReturnsTrue()
	{
		$word = "test";
		$url = "http://www.google.com";
		$expireTime = 60;
		$created = 1462504200;

		$link = new Link();
		$link->setWord($word);
		$link->setUrl($url);
		$link->setExpireTime($expireTime);
		$link->setCreated($created);
		$this->assertTrue($link->hasExpired());
	}

	public function addLinkReturnsExpectedRemainingMinutesDataProvider()
	{
		$now = time();
		$pastTime = 1111111111;

		return [
        	[$now, 50, 50],
        	[$pastTime, 50, 0]
        ];
	}

	/**
     * @dataProvider addLinkReturnsExpectedRemainingMinutesDataProvider
     */
	public function testLinkReturnsExpectedRemainingMinutes($created, $expireTime, $expectedRemainingMinutes)
	{
		$word = "test";
		$url = "http://www.google.com";

		$link = new Link();
		$link->setWord($word);
		$link->setUrl($url);
		$link->setExpireTime($expireTime);
		$link->setCreated($created);
		$this->assertEquals($link->getRemainingMinutes(), $expectedRemainingMinutes);
	}

}