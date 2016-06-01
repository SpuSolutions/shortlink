<?php

namespace App\Link;

use App\Security\Encryption;


class LinkService
{
    private $linkDao;
    private $linkFactory;
    private $encryption;

    public function __construct(LinkDaoInterface $linkDao, LinkFactory $linkFactory, Encryption $encryption=null)
    {
        $this->linkDao = $linkDao;
        $this->linkFactory = $linkFactory;
        $this->encryption = $encryption;
    }

    /**
     * Check if a link exists for the supplied $word
     * @param null $word
     * @return Link|bool
     */
    public function getByWord($word = null)
    {
        if ($word === null || empty($word)){ return false; }

        $link = $this->linkDao->getByWord($word);

        // Return link if it hasn't expired and contains valid data
        if ($link !== false && !$link->hasExpired()){
            
            if ($link->getPasswordProtected()) {
                //we need encryption
                $password = 'ppp';
                //TODO: same level than encryption
                $link->setPasswordProtected($password);
                $link->setUrl($this->encryption->decrypt($link->getUrl(), $password));
            }
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
        $link->setPasswordProtected($password);

        if ($password != ''){
            //we need encryption
            $link->setPasswordProtected($password);
            $url = $this->encryption->encrypt($url,$password);
        }
        $link->setWord($word);
        $link->setUrl($url);
        $link->setExpireTime($data->expireTime);
        $link->setCreated(time());

        return $this->linkDao->create($link);

    }

}
