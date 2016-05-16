<?php

namespace App\Link;
use App\Link\LinkDaoInterface;
use App\Link\LinkFactory;

class LinkService {

	private $linkDao;
	private $linkFactory;
	
	public function __construct(LinkDaoInterface $linkDao, LinkFactory $linkFactory)
	{
		$this->linkDao = $linkDao;
		$this->linkFactory = $linkFactory;
	}

	/**
	 *	Check if a link exists for the supplied $word
	 */
	public function getByWord($word = null)
	{
		if($word === null || empty($word)){ return false; }

		$link = $this->linkDao->getByWord($word);

		//	Return link if it hasn't expired and contains valid data
		if($link !== false && $link->isValid() && !$link->hasExpired()){
			return $link;
		} else {
			return false;
		}
	}

	/**
	 *	Create a new link
	 */
	public function create($word = null, $data = null)
	{
		if($word === null || empty($word) || empty((array)$data) || !is_object($data)){ return false; }
		
		$link = $this->linkFactory->create();
		$link->setWord($word);
		$link->setUrl($data->url);
		$link->setExpireTime($data->expireTime);
		$link->setCreated(time());

		//	If the link holds valid data, send it to the DAO layer to try to save it
		if($link->isValid()){
			return $this->linkDao->create($link);
		} else {
			return false;
		}
		
	}
}