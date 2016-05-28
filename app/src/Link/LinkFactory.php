<?php

namespace App\Link;
use App\Link\Link;

class LinkFactory {

	public function create()
	{
		return new Link();
	}
}