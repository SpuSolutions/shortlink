<?php

namespace App;

interface ValidatorInterface {
	public function isValid($item);
	public function getErrors();
}