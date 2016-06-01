<?php

use App\Link\LinkValidator;

class LinkValidatorTest extends \PHPUnit_Framework_TestCase {
    
    public function setUp()
    {
        $this->settings = [
            'reservedWords' => ['about', 'new'],
            'expireTime' => ['min' => 1, 'max' => 60],   // expire time in minutes
            'word' => ['maxLength' => 10]
        ];
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

        $link = new stdClass();
        $link->word = $word;
        $link->url = $url;
        $link->expireTime = $expireTime;

        $linkValidator = new LinkValidator($this->settings);

        $this->assertFalse($linkValidator->isValid($link));
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

        $link = new stdClass();
        $link->word = $word;
        $link->url = $url;
        $link->expireTime = $expireTime;

        $linkValidator = new LinkValidator($this->settings);
        $this->assertTrue($linkValidator->isValid($link));
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

        $link = new stdClass();
        $link->word = $word;
        $link->url = $url;
        $link->expireTime = $expireTime;
        
        $linkValidator = new LinkValidator($this->settings);
        $this->assertFalse($linkValidator->isValid($link));
    }
    
    
    public function testAddingValidUrlAndTestingValidityReturnsTrue()
    {
        $word = "test";
        $url = "http://www.google.com";
        $expireTime = 60;

        $link = new stdClass();
        $link->word = $word;
        $link->url = $url;
        $link->expireTime = $expireTime;
        
        $linkValidator = new LinkValidator($this->settings);
        $this->assertTrue($linkValidator->isValid($link));
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


        $link = new stdClass();
        $link->word = $word;
        $link->url = $url;
        $link->expireTime = $expireTime;
        
        $linkValidator = new LinkValidator($this->settings);
        $this->assertFalse($linkValidator->isValid($link));
    }
    
    
    public function testAddingValidExpireTimeAndTestingValidityReturnsTrue()
    {
        $word = "test";
        $url = "http://www.google.com";
        $expireTime = "60";

        $link = new stdClass();
        $link->word = $word;
        $link->url = $url;
        $link->expireTime = $expireTime;
        
        $linkValidator = new LinkValidator($this->settings);
        $this->assertFalse($linkValidator->isValid($link));
    }

//    public function addAddingInvalidCreatedTimeAndTestingValidityReturnsFalseDataProvider()
//    {
//        return [
//            ['1212d12f12f'],
//            ['123456789'],
//            [123456789],
//            [12345678910]
//        ];
//    }
//    
//    
//
//    /**
//     * @dataProvider addAddingInvalidCreatedTimeAndTestingValidityReturnsFalseDataProvider
//     */
//    public function testAddingInvalidCreatedTimeAndTestingValidityReturnsFalse($created)
//    {
//        $word = "test";
//        $url = "http://www.google.com";
//        $expireTime = 60;
//
//
//        $this->link->setWord($word);
//        $this->link->setUrl($url);
//        $this->link->setExpireTime($expireTime);
//        $this->link->setCreated($created);
//        
//        $linkValidator = new LinkValidator();
//        $this->assertFalse($linkValidator->isValid($this->link));
//    }
//    
//    
//    public function testAddingValidCreatedTimeAndTestingValidityReturnsTrue()
//    {
//        $word = "test";
//        $url = "http://www.google.com";
//        $expireTime = 60;
//        $created = 1463504590;
//
//
//        $this->link->setWord($word);
//        $this->link->setUrl($url);
//        $this->link->setExpireTime($expireTime);
//        $this->link->setCreated($created);
//        
//        $linkValidator = new LinkValidator();
//        $this->assertTrue($linkValidator->isValid($this->link));
//    }
//
//    public function testAddingInvalidPasswordProctedAndTestingValidityReturnsFalse()
//    {
//        $word = "test";
//        $url = "http://www.google.com";
//        $expireTime = 60;
//        $passwordProtected = "false";
//
//
//        $this->link->setWord($word);
//        $this->link->setUrl($url);
//        $this->link->setExpireTime($expireTime);
//        $this->link->setPasswordProtected($passwordProtected);
//        
//        $linkValidator = new LinkValidator();
//        $this->assertFalse($linkValidator->isValid($this->link));
//    }
//
//    public function testAddingValidPasswordProctedAndTestingValidityReturnsTrue()
//    {
//        $word = "test";
//        $url = "http://www.google.com";
//        $expireTime = 60;
//        $passwordProtected = false;
//
//
//        $this->link->setWord($word);
//        $this->link->setUrl($url);
//        $this->link->setExpireTime($expireTime);
//        $this->link->setPasswordProtected($passwordProtected);
//        
//        $linkValidator = new LinkValidator();
//        $this->assertTrue($linkValidator->isValid($this->link));
//    }

    public function testCreatingNewLinkWithoutAddingPropertiesAndTestingValidityReturnsFalse()
    {        
        $link = new stdClass();
        $link->word = null;
        $link->url = null;
        $link->expireTime = null;
        
        $linkValidator = new LinkValidator($this->settings);
        $this->assertFalse($linkValidator->isValid($link));
    }
    
    public function testCreatingNewLinkWithReservedWordAndTestingValidityReturnsFalse()
    {        
        $link = new stdClass();
        $link->word = 'about';
        $link->url = 'http://www.google.com';
        $link->expireTime = 60;
        
        $linkValidator = new LinkValidator($this->settings);
        $this->assertFalse($linkValidator->isValid($link));
    }

}