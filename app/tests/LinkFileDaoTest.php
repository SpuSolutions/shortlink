<?php

use App\Link\LinkFileDao;
use App\Link\LinkFactory;

class LinkFileDaoTest extends \PHPUnit_Framework_TestCase {

    private $linkDao;

    public function setUp()
    {
        $this->linkFactory = new LinkFactory();
        $this->linkDao = $this->getMockBuilder('App\Link\LinkDaoInterface')->setConstructorArgs([new App\Link\LinkFactory(), []])->getMock();
    }

    public function testGettingLinkGivenAnExistingWordReturnsALinkObject()
    {
        $word = "myWord";

        $this->linkDao->method("getByWord")->with("myWord")->will($this->returnCallback(
            function() {
                $l = $this->linkFactory->create();
                $l->setWord("myWord");
                $l->setUrl("http://www.google.com");
                return $l;
            }
        ));

        $linkService = new LinkFileDao($this->linkDao, $this->linkFactory);
        $link = $linkService->getByWord($word);
        $this->assertInstanceOf('App\Link\Link', $link);

    }
}