<?php

use App\Link;
use App\LinkDaoInterface;
use App\LinkService;
use App\LinkFactory;

class LinkServiceTest extends \PHPUnit_Framework_TestCase {

	private $linkDao;

	public function setUp()
	{
		$this->linkFactory = new LinkFactory();
		$this->linkDao = $this->getMockBuilder('App\LinkDaoInterface')->setConstructorArgs([new App\LinkFactory()])->getMock();
	}

	public function testGettingLinkGivenAnExistingWordReturnsALinkObject()
	{
		$word = "myWord";

		$this->linkDao->method("getByWord")->with("myWord")->willReturn(new Link());

		$linkService = new LinkService($this->linkDao, $this->linkFactory);
		$link = $linkService->getByWord($word);
		$this->assertInstanceOf('App\Link', $link);

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

	public function testCreatingLinkGivenAWordAndDataReturnsALinkObject()
	{
		$word = "testWord";
		$data = (object)['url' => 'www', 'expireTime' => 60];

		$this->linkDao->method("create")->with()->willReturn(new Link());

		$linkService = new LinkService($this->linkDao, $this->linkFactory);
		$link = $linkService->create($word, $data);
		$this->assertInstanceOf('App\Link', $link);
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