<?php

namespace App\Link;
use App\Link\Link;

interface LinkDaoInterface {
	public function create(Link $link);
	public function getByWord($word);
}