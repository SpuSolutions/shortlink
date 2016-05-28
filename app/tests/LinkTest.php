<?php

use App\Link\Link;
use App\Link\LinkValidator;
use App\Link\LinkFactory;

class LinkTest extends \PHPUnit_Framework_TestCase {
    
    public function setUp()
    {
        $this->linkFactory = new LinkFactory(new LinkValidator());
    }

    public function testCreatingNewLinkAndAddingWordReturnsExpectedWord()
    {
        $word = "testWord";
        $url = "http://www.google.com";
        $expireTime = 60;
        $expectedWord = "testWord";

        $link = $this->linkFactory->create();
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

        $link = $this->linkFactory->create();
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

        $link = $this->linkFactory->create();
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

        $link = $this->linkFactory->create();
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

        $link = $this->linkFactory->create();
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

        $link = $this->linkFactory->create();
        $link->setWord($word);
        $link->setUrl($url);
        $link->setExpireTime($expireTime);
        $link->setCreated($created);
        $link->setPasswordProtected($passwordProtected);
        $this->assertEquals($expectedPasswordProtected, $link->getPasswordProtected());
    }

    public function testCreatingNewLinkSetsNonEmptyCreatedTime()
    {
        $link = $this->linkFactory->create();
        $this->assertNotEmpty($link->getCreated());
    }

    public function testCreatingLinkAndCheckingHasExpiredReturnsFalse()
    {
        $word = "test";
        $url = "http://www.google.com";
        $expireTime = 60;

        $link = $this->linkFactory->create();
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

        $link = $this->linkFactory->create();
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

        $link = $this->linkFactory->create();
        $link->setWord($word);
        $link->setUrl($url);
        $link->setExpireTime($expireTime);
        $link->setCreated($created);
        $this->assertEquals($link->getRemainingMinutes(), $expectedRemainingMinutes);
    }

}
