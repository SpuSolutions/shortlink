<?php
/**
 * Created by PhpStorm.
 * User: salvob
 * Date: 18/05/2016
 * Time: 23:32
 */

namespace App\Security;


use Exception;

class Encryption{

    private $secretKey;
    private $method = "aes-256-cbc";


    public function __construct($secretKey){
        $this->secretKey = $secretKey;

    }

   /**
     * @param string $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }


    /**
     * Get the list of cipher methods supported by openssl module
     * @return array
     */
    public function getCipherMethod(){
        return openssl_get_cipher_methods();
    }

    /**
     * @param $string
     * @return mixed|string
     */
    public  function safe_b64encode($string) {
        $data = base64_encode($string);
        $data = str_replace(array('+','/','='),array('-','_',''),$data);
        return $data;
    }
    public function safe_b64decode($string) {
        $data = str_replace(array('-','_'),array('+','/'),$string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }

    /**
     * @param $value
     * @return bool|string
     * @throws Exception
     */
    public function encode($value){
        if(!$value){return false;}
        $text = $value;
        //temporary iv
        $ivlen = openssl_cipher_iv_length($this->method);
        $isCryptoStrong = false; // Will be set to true by the function if the algorithm used was cryptographically secure
        $iv = openssl_random_pseudo_bytes($ivlen, $isCryptoStrong);
        if(!$isCryptoStrong)
            throw new Exception("Non-cryptographically strong algorithm used for iv ".$ivlen." generation with method: ".$this->method.". This IV: ".$iv." is not safe to use.");

        $encrypted = openssl_encrypt($text, $this->method, $this->secretKey, OPENSSL_ZERO_PADDING, $iv);
        return trim($this->safe_b64encode($encrypted));

    }

    /**
     * @param $value
     * @return bool|string
     */
    public function decode($value){
        if(!$value){return false;}
        $encrypted = $this->safe_b64decode($value);
        //temporary iv
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);

        $text = openssl_decrypt($encrypted, $this->method, $this->secretKey, $iv);
        return trim($this->safe_b64encode($text));

    }


}