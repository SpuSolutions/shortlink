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
    private $settings;

    public function __construct(Array $settings)
    {
        $this->settings = $settings;

    }
    /**
     * https://paragonie.com/blog/2015/05/if-you-re-typing-word-mcrypt-into-your-code-you-re-doing-it-wrong
     * @param $message
     * @param $key
     * @return string
     * @throws Exception
     */
    public function encrypt($message, $key)
    {
        //multibyte string len check
        if (mb_strlen($key, $this->settings['multibyte_key_len']) !== $this->settings['mb_strlen']) {
            $key = hash($this->settings['hash_method'], $key);
            //throw new Exception("Needs a 256-bit key!");
        }
        $ivsize = openssl_cipher_iv_length($this->settings['method']);
        $iv = openssl_random_pseudo_bytes($ivsize);

        $ciphertext = openssl_encrypt(
            $message,
            $this->settings['method'],
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
    public function decrypt($message, $key)
    {
        if (mb_strlen($key, $this->settings['multibyte_key_len']) !== $this->settings['mb_strlen']) {
            $key = hash($this->settings['hash_method'], $key);
            //throw new Exception("Needs a 256-bit key!");
        }
        $ivsize = openssl_cipher_iv_length($this->settings['method']);
        $iv = mb_substr($message, 0, $ivsize, $this->settings['multibyte_key_len']);
        $ciphertext = mb_substr($message, $ivsize, null, $this->settings['multibyte_key_len']);

        return openssl_decrypt(
            $ciphertext,
            $this->settings['method'],
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );
    }

}