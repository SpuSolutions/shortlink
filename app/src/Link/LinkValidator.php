<?php

namespace App\Link;

use App\ValidatorInterface;


Class LinkValidator implements ValidatorInterface
{

    private $errors = [];
    private $settings;

    public function __construct($settings)
    {
        $this->settings = $settings;
    }

    public function isValid($link)
    {
        //  Empty any errors from a previous validation
        $this->errors = [];

        // Check if there are alphanumeric char
        if (!ctype_alpha($link->word)) {
            $this->setError('word', 'Word is not valid. Must contain only letters');
        }

        // Check word is empty
        if (empty($link->word)) {
            $this->setError('word', 'Word is not valid. Word is empty');
        }
        // Check word length
        if (strlen($link->word) > $this->settings['word']['maxLength']) {
            $this->setError('word', 'Word is not valid. Word must be shorter than ' . $this->settings['word']['maxLength'] . ' characters.');
        }


        //  Check the word is not a reserved one
        if (in_array($link->word, $this->settings['reservedWords'])) {
            $this->setError('word', 'Word: Please choose another memorable word');
        }

        //  Check the url is in a valid format
        if (!(!filter_var($link->url, FILTER_VALIDATE_URL) === false) || empty($link->url)) {
            $this->setError('url', 'Url is not valid. Eg: http://www.example.com.');
        }

        //  Check the expireTime is a number between expireTimeMin and expireTimeMax
        if (!is_int($link->expireTime) || $link->expireTime < $this->settings['expireTime']['min'] || $link->expireTime > $this->settings['expireTime']['max'] || empty($link->expireTime)) {
            $this->setError('expireTime', 'Expire time is not valid. Must be between 1 and 60');
        }

        //  Compare passwords
        if(isset($link->password) && strcmp($link->password, $link->passwordConfirm ) != 0){
            $this->setError('word', 'Passwords must be equals');
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
