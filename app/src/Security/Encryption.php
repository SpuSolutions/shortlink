<?php
/**
 * Created by PhpStorm.
 * User: salvob
 * Date: 18/05/2016
 * Time: 23:32
 */

namespace App\Security;


use Exception;

class Encryption
{

    private $secretKey;
    private $method = "aes-256-cbc";
    private $iv;
    const METHOD = 'aes-256-cbc';


    public function __construct($secretKey)
    {
        $this->secretKey = $secretKey;

    }


    /**
     * @param mixed $iv
     */
    private function _setIv($iv)
    {
        $this->iv = $iv;
    }

    /**
     * @return mixed
     */
    public function getIv()
    {
        return $this->iv;
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
    public function getCipherMethod()
    {
        return openssl_get_cipher_methods();
    }

    /**
     * @param $string
     * @return mixed|string
     */
    public function safe_b64encode($string)
    {
        $data = base64_encode($string);
        $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);
        return $data;
    }

    public function safe_b64decode($string)
    {
        $data = str_replace(array('-', '_'), array('+', '/'), $string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }

    /**
     * @param $message
     * @param $key
     * @return string
     * @throws Exception
     */
    public static function encrypt($message, $key)
    {
        if (mb_strlen($key, '8bit') !== 32) {
            $key = hash("sha256", $key);
            //throw new Exception("Needs a 256-bit key!");
        }
        $ivsize = openssl_cipher_iv_length(self::METHOD);
        $iv = openssl_random_pseudo_bytes($ivsize);

        $ciphertext = openssl_encrypt(
            $message,
            self::METHOD,
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );

        return $iv . $ciphertext;
    }

    /**
     * @param $message
     * @param $key
     * @return string
     */
    public static function decrypt($message, $key)
    {
        if (mb_strlen($key, '8bit') !== 32) {
            $key=hash("sha256", $key);
            //throw new Exception("Needs a 256-bit key!");
        }
        $ivsize = openssl_cipher_iv_length(self::METHOD);
        $iv = mb_substr($message, 0, $ivsize, '8bit');
        $ciphertext = mb_substr($message, $ivsize, null, '8bit');

        return openssl_decrypt(
            $ciphertext,
            self::METHOD,
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );
    }

    /**
     * @param $value
     * @return bool|string
     * @throws Exception
     */
    public function encode($value)
    {
        if (!$value) {
            return false;
        }
        $text = $value;
        //temporary iv
        $ivlen = openssl_cipher_iv_length($this->method);
        $isCryptoStrong = false; // Will be set to true by the function if the algorithm used was cryptographically secure
        $iv = openssl_random_pseudo_bytes($ivlen, $isCryptoStrong);
        if (!$isCryptoStrong)
            throw new Exception("Non-cryptographically strong algorithm used for iv " . $ivlen . " generation with method: " . $this->method . ". This IV: " . $iv . " is not safe to use.");

        $this->_setIv($iv);
        $encrypted = openssl_encrypt($text, $this->method, $this->secretKey, OPENSSL_ZERO_PADDING, $iv);
        return trim($this->safe_b64encode($encrypted));

    }

    /**
     * @param $value
     * @return bool|string
     */
    public function decode($value)
    {
        if (!$value) {
            return false;
        }
        $encrypted = $this->safe_b64decode($value);
        //temporary iv
        $iv = $this->getIv();
        $text = openssl_decrypt($encrypted, $this->method, $this->secretKey, OPENSSL_ZERO_PADDING, $iv);
        return trim($this->safe_b64encode($text));

    }


}