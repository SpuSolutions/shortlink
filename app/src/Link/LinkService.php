<?php

namespace App\Link;

use App\Link\LinkDaoInterface;
use App\Link\LinkFactory;
use App\Security\Encryption;

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
        if ($word === null || empty($word)) {
            return false;
        }

        $link = $this->linkDao->getByWord($word);


        //	Return link if it hasn't expired and contains valid data
        if ($link !== false && $link->isValid() && !$link->hasExpired()) {
            if($link->getPasswordProtected()){
                // need to ask a password


            }
            return $link;
        } else {
            return false;
        }
    }


    /**
     * Create a new link
     * @param null $word
     * @param null $data
     * @return bool
     */
    public function create($word = null, $data = null)
    {
        if ($word === null || empty($word) || empty((array)$data) || !is_object($data)) {
            return false;
        }
        $url = $data->url;
        $password = $data->password;


        $link = $this->linkFactory->create();

        if ($password != ''){
            //we need encryption
            $link->setPasswordProtected($password);
            $encryptClass = new Encryption($password);
            $url = $encryptClass->encrypt($url,$password);

        }

        $link->setWord($word);
        $link->setUrl($url);
        $link->setExpireTime($data->expireTime);
        $link->setCreated(time());

        //	If the link holds valid data, send it to the DAO layer to try to save it
        if ($link->isValid()) {
            return $this->linkDao->create($link);
        } else {
            return false;
        }

    }
}