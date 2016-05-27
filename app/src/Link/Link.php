<?php

namespace App\Link;

Class Link {
	
	private $word;
	private $url; //might be encrypted
	private $expireTime = 60;
	private $passwordProtected = false;
	private $created;

	/**
	 * Link constructor.
     */
	public function __construct()
	{
		$this->created = time();
	}

	/**
	 * @param $word
     */
	public function setWord($word)
	{
		$this->word = $word;
	}

	/**
	 * @return mixed
     */
	public function getWord()
	{
		return $this->word;
	}

	/**
	 * @param $url
     */
	public function setUrl($url)
	{
		$this->url = $url;
	}

	/**
	 * @return mixed
     */
	public function getUrl()
	{
		return $this->url;
	}

	/**
	 * @param $expireTime
     */
	public function setExpireTime($expireTime)
	{
		$this->expireTime = $expireTime;
	}

	/**
	 * @return int
     */
	public function getExpireTime()
	{
		return $this->expireTime;
	}

	/**
	 * @param $created
     */
	public function setCreated($created)
	{
		$this->created = $created;
	}

	/**
	 * @return int
     */
	public function getCreated()
	{
		return $this->created;
	}

	/**
	 * @param $passwordProtected
     */
	public function setPasswordProtected($password)
	{
		$passwordProtected = false;
		if($password!= ''){
			$passwordProtected = true;
		}
		$this->passwordProtected = $passwordProtected;
	}

	/**
	 * @return bool
     */
	public function getPasswordProtected()
	{
		return $this->passwordProtected;
	}

	/**
	 * @return string
     */
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

	/**
	 * @return bool
     */
	public function hasExpired()
	{
		return time() > $this->created + ($this->expireTime * 60);
	}

	/**
	 * @return float|int
     */
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