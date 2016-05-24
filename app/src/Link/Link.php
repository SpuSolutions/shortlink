<?php

namespace App\Link;

Class Link {
	
	private $word;
	private $url;
	private $expireTime = 60;
	private $passwordProtected = false;
	private $created;

	public function __construct()
	{
		$this->created = time();
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

	public function setPasswordProtected($passwordProtected)
	{
		$this->passwordProtected = $passwordProtected;
	}

	public function getPasswordProtected()
	{
		return $this->passwordProtected;
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

	public function getRemainingMinutes()
	{
		$remainingMinutes = ceil(($this->created + $this->expireTime * 60 - time()) / 60);
		if($remainingMinutes >= 0) {
			return $remainingMinutes;
		} else {
			return 0;
		}
	}

}