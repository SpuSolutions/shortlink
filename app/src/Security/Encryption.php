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

    const METHOD = 'aes-256-cbc';
    private $method = "aes-256-cbc";


    public function __construct($secretKey)
    {
        $this->secretKey = $secretKey;

    }

    /**
     * https://paragonie.com/blog/2015/05/if-you-re-typing-word-mcrypt-into-your-code-you-re-doing-it-wrong
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
            $key = hash("sha256", $key);
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

}