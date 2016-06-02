<?php

use App\Link\LinkFileDao;
use App\Link\LinkFactory;

class LinkFileDaoTest extends \PHPUnit_Framework_TestCase {

    private $linkDao;

    public function setUp()
    {
        $this->linkFactory = new LinkFactory();
    }
    
    public function testOne()
    {
        $this->assertTrue(true);
    }


}