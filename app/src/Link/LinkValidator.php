<?php

namespace App\Link;

use App\ValidatorInterface;


Class LinkValidator implements ValidatorInterface {
    
    private $errors = [];

    public function __construct(){}

    public function isValid($link)
    {
        //  Empty any errors from a previous validation
        $this->errors = [];
        
        if(!ctype_alpha($link->word) || empty($link->word) || strlen($link->word) > 10){
            $this->setError('word', 'Word: '.$link->word.' is not valid. Must contain only letters or be shorter than 10 characters.');
        }
        
        if(!(!filter_var($link->url, FILTER_VALIDATE_URL) === false) || empty($link->url)){
            $this->setError('url', 'Url is not valid. Eg: http://www.example.com.');
        }
        
        if(!is_int($link->expireTime) || $link->expireTime < 1 || $link->expireTime > 60 || empty($link->expireTime)){
            $this->setError('expireTime', 'Expire time is not valid. Must be between 1 and 60');
        }
        
        return empty($this->getErrors());
    }

    public function getErrors()
    {
        return $this->errors;
    }
    
    public function getErrorsByProperty($property)
    {
        return $this->errors[$property];
    }
    
    private function setError($property, $message)
    {
        $this->errors[$property] = $message;
    }
}
