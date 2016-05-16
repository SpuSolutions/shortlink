<?php

namespace App\Link;
use App\Link\Link;
use App\Link\LinkFactory;

interface LinkDaoInterface {

	public function __construct(LinkFactory $linkFactory, Array $settings);
	public function create(Link $link);
	public function getByWord($word);
}