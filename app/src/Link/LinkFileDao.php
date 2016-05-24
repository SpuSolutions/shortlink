<?php

namespace App\Link;
use App\Link\Link;
use App\Link\LinkFactory;

Class LinkFileDao implements LinkDaoInterface {

	private $settings;
	private $linkFactory;

	public function __construct(LinkFactory $linkFactory, Array $settings)
	{
		$this->linkFactory = $linkFactory;
		$this->settings = $settings;
	}
	
	/**
	 *	Save a link to the filesystem
	 */
	public function create(Link $link)
	{
		$word = $link->getWord();
		$filePath = $this->buildFilePath($word);

		// Only proceed to create the file if a file with the same name doesn't exist or exists and has expired
		$testExistingLink = $this->getByWord($word);
		if($testExistingLink === false || ($testExistingLink && $testExistingLink->hasExpired())){

			// Proceed to create the file
			// If file creation fails return false
			if(!$this->saveFileContent($filePath, $link)){ 
				return false; 
			} else {
				return $link;	
			}			

		} else {
			return false;
		}
	}

	/**
	 * Search for a link in the filesystem
	 */
	public function getByWord($word)
	{
		$filePath = $this->buildFilePath($word);
		if(file_exists($filePath)){

			$fileData = $this->getFileContent($filePath);
			
			// Build the link object
			$link = $this->linkFactory->create();
			$link->setWord($word);
			$link->setUrl($fileData->url);
			$link->setExpireTime($fileData->expireTime);
			$link->setCreated($fileData->created);

			return $link;
		} else {

			return false;
		}
	}

	/**
	 * Build the file path for a $word
	 */
	private function buildFilePath($word)
	{
		return $this->settings['upload_path'].$word;
	}

	/**
	 * Return a std object of the contents of a file
	 */
	private function getFileContent($filePath)
	{
		$data = file_get_contents($filePath);
		$formattedData = json_decode($data);
		return $formattedData;
	}

	private function saveFileContent($filePath, $link)
	{
		return file_put_contents($filePath, $link);
	}
}