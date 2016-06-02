<?php

use App\Link\LinkService;
use App\Link\LinkFactory;
use App\Security\Encryption;

class LinkServiceTest extends \PHPUnit_Framework_TestCase
{

    private $linkDao;
    private $linkFactory;
    private $encryption;


    public function setUp()
    {
        $this->linkFactory = new LinkFactory();
        $this->linkDao = $this->getMockBuilder('App\Link\LinkDaoInterface')->setConstructorArgs([new App\Link\LinkFactory(), []])->getMock();
        $arraySettings = [
            'method' => "aes-256-cbc",
            'hash_method' => "sha256",
            'multibyte_key_len' => "8bit",
            'mb_strlen' => "8bit"
        ];
        $this->encryption = new Encryption($arraySettings);
    }

    public function testGettingLinkGivenAnExistingWordReturnsALinkObject()
    {
        $word = "myWord";

        $this->linkDao->method("getByWord")->with("myWord")->will($this->returnCallback(
            function () {
                $l = $this->linkFactory->create();
                $l->setWord("myWord");
                $l->setUrl("http://www.google.com");
                return $l;
            }
        ));

        $linkService = new LinkService($this->linkDao, $this->linkFactory);
        $link = $linkService->getByWord($word);
        $this->assertInstanceOf('App\Link\Link', $link);

    }

    public function testGettingLinkGivenNonExistingWordReturnsFalse()
    {
        $word = "myWord";

        $this->linkDao->method("getByWord")->with("myWord")->willReturn(false);

        $linkService = new LinkService($this->linkDao, $this->linkFactory);
        $link = $linkService->getByWord($word);
        $this->assertFalse($link);

    }

    public function testGettingLinkGivenNonExistingEmptyWordReturnsFalse()
    {
        $word = "";

        $this->linkDao->method("getByWord")->with("myWord")->willReturn(false);

        $linkService = new LinkService($this->linkDao, $this->linkFactory);
        $link = $linkService->getByWord($word);
        $this->assertFalse($link);

    }

    public function testGettingLinkGivenWithoutWordReturnsFalse()
    {
        $word = "";

        $this->linkDao->method("getByWord")->with("myWord")->willReturn(false);

        $linkService = new LinkService($this->linkDao, $this->linkFactory);
        $link = $linkService->getByWord();
        $this->assertFalse($link);

    }

    public function testGettingLinkWithCorrectPasswordReturnsALinkObject()
    {
        $word = "myWord";
        $password = "testPassword";

        $this->linkDao->method("getByWord")->with("myWord")->will($this->returnCallback(
            function () {
                $l = $this->linkFactory->create();
                $l->setWord("testWord");
                $e = $this->encryption;
                $l->setPasswordProtected(true);
                $l->setUrl($e->encrypt("http://www.google.com", "testPassword"));
                return $l;
            }
        ));

        $linkService = new LinkService($this->linkDao, $this->linkFactory, $this->encryption);
        $link = $linkService->getByWord($word, "testPassword");
        $this->assertEquals('http://www.google.com', $link->getUrl());

    }

    public function testGettingLinkWithWrongPasswordReturnsALinkObject()
    {
        $word = "myWord";
        $password = "testPassword";

        $this->linkDao->method("getByWord")->with($word)->will($this->returnCallback(
            function () {
                $l = $this->linkFactory->create();
                $l->setWord("testWord");
                $e = $this->encryption;
                $l->setPasswordProtected(true);
                $l->setUrl($e->encrypt("http://www.google.com", "testPassword"));
                return $l;
            }
        ));

        $linkService = new LinkService($this->linkDao, $this->linkFactory, $this->encryption);
        $link = $linkService->getByWord($word, "WrongPassword");
        $this->assertFalse($link->getUrl());

    }

    public function testCreatingWithPasswordGivenAWordAndReturnsALinkObject()
    {
        $word = "testWord";
        $password = "testPassword";
        $data = (object)['url' => 'http://www.google.com', 'expireTime' => 60, 'password' => $password];

        $this->linkDao->method("create")->withAnyParameters()->will($this->returnCallback(
            function () {
                $l = $this->linkFactory->create();
                $l->setWord("testWord");
                $e = $this->encryption;
                $l->setPasswordProtected(true);
                $l->setUrl($e->encrypt("http://www.google.com", "testPassword"));
                return $l;
            }
        ));

        $linkService = new LinkService($this->linkDao, $this->linkFactory, $this->encryption);
        $link = $linkService->create($word, $data);
        var_dump($link);
        $this->assertInstanceOf('App\Link\Link', $link);
    }

    public function testCreatingLinkGivenAWordAndDataReturnsALinkObject()
    {
        $word = "testWord";
        $data = (object)['url' => 'http://www.google.com', 'expireTime' => 60];

        $this->linkDao->method("create")->withAnyParameters()->will($this->returnCallback(
            function () {
                $l = $this->linkFactory->create();
                $l->setWord("testWord");
                $l->setUrl("http://www.google.com");
                return $l;
            }
        ));

        $linkService = new LinkService($this->linkDao, $this->linkFactory);
        $link = $linkService->create($word, $data);
        $this->assertInstanceOf('App\Link\Link', $link);
    }

    public function testCreatingLinkGivenAnExistingWordAndDataReturnsFalse()
    {
        $word = "testWord";
        $data = (object)['url' => 'www', 'expireTime' => 60];

        $this->linkDao->method("create")->with()->willReturn(false);

        $linkService = new LinkService($this->linkDao, $this->linkFactory);
        $link = $linkService->create($word, $data);
        $this->assertFalse($link);
    }

    public function testCreatingLinkGivenAnEmptyWordAndEmptyDataReturnsFalse()
    {
        $word = "";
        $data = (object)[];

        $this->linkDao->method("create")->with($word, $data)->willReturn(false);

        $linkService = new LinkService($this->linkDao, $this->linkFactory);
        $link = $linkService->create($word, $data);
        $this->assertFalse($link);
    }

    public function testCreatingLinkGivenAWordAndEmptyDataReturnsFalse()
    {
        $word = "testWord";
        $data = (object)[];

        $this->linkDao->method("create")->with()->willReturn(false);

        $linkService = new LinkService($this->linkDao, $this->linkFactory);
        $link = $linkService->create($word, $data);
        $this->assertFalse($link);
    }

    public function testCreatingLinkGivenAWordAndANonObjectDataReturnsFalse()
    {
        $word = "testWord";
        $data = ["url" => "myurl.net"];

        $this->linkDao->method("create")->with()->willReturn(false);

        $linkService = new LinkService($this->linkDao, $this->linkFactory);
        $link = $linkService->create($word, $data);
        $this->assertFalse($link);
    }

}