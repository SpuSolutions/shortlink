<?php
/**
 * Created by PhpStorm.
 * User: salvob
 * Date: 18/05/2016
 * Time: 23:32
 */

namespace App\Security;


class Encryption{

    private $secretKey;
    private $method;

    public function __construct(){

    }

    /**
     * Get the list of cipher methods supported by openssl module
     * @return array
     */
    public function getCipherMethod(){
        return openssl_get_cipher_methods();
    }

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

    public function encode($value){
        if(!$value){return false;}
        $text = $value;
        //temporary iv
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);

        $encrypted = openssl_encrypt($text, $this->method, $this->secretKey, $iv);
        return trim($this->safe_b64encode($encrypted));

    }

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