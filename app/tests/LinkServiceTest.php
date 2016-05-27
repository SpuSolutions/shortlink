<?php

use App\Link\Link;
use App\Link\LinkDaoInterface;
use App\Link\LinkService;
use App\Link\LinkFactory;

class LinkServiceTest extends \PHPUnit_Framework_TestCase
{

    private $linkDao;

    public function setUp()
    {
        $this->linkFactory = new LinkFactory();
        $this->linkDao = $this->getMockBuilder('App\Link\LinkDaoInterface')->setConstructorArgs([new App\Link\LinkFactory(), []])->getMock();
    }


		$this->linkDao->method("getByWord")->with("myWord")->will($this->returnCallback(
			function() { 
				$l = $this->linkFactory->create();
				$l->setWord("myWord"); 
				$l->setUrl("http://www.google.com");
				return $l; 
			}
		));

        $this->linkDao->method("getByWord")->with("myWord")->will($this->returnCallback(
            function () {
                $l = new Link();
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

		$this->linkDao->method("create")->withAnyParameters()->will($this->returnCallback(
			function() { 
				$l = $this->linkFactory->create();
				$l->setWord("testWord");
				$l->setUrl("http://www.google.com");
				return $l; 
			}
		));

        $expected = $this->linkDao->method("create")->withAnyParameters()->will($this->returnCallback(
            function () {
                $l = new Link();
                $l->setWord("testWord");

                $e = new \App\Security\Encryption("testPassword");
                $l->setPasswordProtected(true);
                $l->setUrl($e->encrypt("http://www.google.com", "testPassword"));

                return $l;
            }
        ));

        $linkService = new LinkService($this->linkDao, $this->linkFactory);

        $link = $linkService->create($word, $data);

        $this->assertInstanceOf('App\Link\Link', $link);
        



    }

    public function testCreatingLinkGivenAWordAndDataReturnsALinkObject()
    {
        $word = "testWord";
        $data = (object)['url' => 'http://www.google.com', 'expireTime' => 60];

        $this->linkDao->method("create")->withAnyParameters()->will($this->returnCallback(
            function () {
                $l = new Link();
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