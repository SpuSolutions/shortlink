<?php

namespace App\Link;

Class Link {
	
	private $word;
	private $url;
	private $expireTime;
	private $passwordProtected = false;
	private $created;
	private $errors;

	public function __construct()
	{
		$this->created = time();
		$this->errors = array();
	}

	public function setWord($word)
	{
		$this->word = $word;
	}

	public function getWord()
	{
		return $this->word;
	}

	public function setUrl($url)
	{
		$this->url = $url;
	}

	public function getUrl()
	{
		return $this->url;
	}

	public function setExpireTime($expireTime)
	{
		$this->expireTime = $expireTime;
	}

	public function getExpireTime()
	{
		return $this->expireTime;
	}

	public function setCreated($created)
	{
		$this->created = $created;
	}

	public function getCreated()
	{
		return $this->created;
	}

	public function setPasswordProtected($created)
	{
		$this->passwordProtected = $passwordProtected;
	}

	public function getPasswordProtected()
	{
		return $this->passwordProtected;
	}

	public function isValid()
	{
		return $this->isValidWord() && $this->isValidUrl() && $this->isValidExpireTime();
	}

	public function __toString()
	{
		$link = [
			'word' => $this->getWord(),
			'url' => $this->getUrl(),
			'expireTime' => $this->getExpireTime(),
			'created' => $this->getCreated(),
			'passwordProtected' => $this->getPasswordProtected()
		];
		return json_encode($link);
	}

	public function hasExpired()
	{
		return time() > $this->created + ($this->expireTime * 60);
	}

	public function getErrors()
	{
		return $this->errors;
	}

	public function getRemainingMinutes()
	{
		return ceil(($this->created + $this->expireTime * 60 - time()) / 60);
	}

	private function setError($message)
	{
		$this->errors[] = $message;
	}

	private function isValidWord()
	{
		return (ctype_alpha($this->word));
	}

	private function isValidUrl()
	{
		return (!filter_var($this->url, FILTER_VALIDATE_URL) === false);
	}

	private function isValidExpireTime()
	{
		return (is_int($this->expireTime));
	}



	

}