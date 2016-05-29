<?php

namespace App\Link;

use App\Security\Encryption;
use Exception;

class LinkService
{

    private $linkDao;
    private $linkFactory;

    public function __construct(LinkDaoInterface $linkDao, LinkFactory $linkFactory)
    {
        $this->linkDao = $linkDao;
        $this->linkFactory = $linkFactory;
    }

    /**
     * Check if a link exists for the supplied $word
     * @param null $word
     * @return bool
     */
    public function getByWord($word = null)
    {
        if ($word === null || empty($word)){ return false; }

        $link = $this->linkDao->getByWord($word);

        // Return link if it hasn't expired and contains valid data
        if ($link !== false && !$link->hasExpired()){
            return $link;
        } else {
            return false;
        }
    }

    /**
     * 	Create a new link
     */
    public function create($word = null, $data = null)
    {
        if ($word === null || empty($word) || empty((array) $data) || !is_object($data)){
            return false;
        }
        $url = $data->url;
        $password = isset($data->password)?$data->password:'';
        $link = $this->linkFactory->create();

        if ($password !== ''){
            //we need encryption
            $link->setPasswordProtected(true);
            $encryptClass = new Encryption($password);
            $url = $encryptClass->encrypt($url,$password);
        }

        $link->setUrl($url);
        $link->setExpireTime($data->expireTime);
        $link->setCreated(time());

        return $this->linkDao->create($link);

    }

}
