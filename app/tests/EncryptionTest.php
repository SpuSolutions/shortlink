<?php

use App\Security\Encryption;

/**
 * Created by PhpStorm.
 * User: salvob
 * Date: 19/05/2016
 * Time: 00:53
 */
class EncryptionTest extends PHPUnit_Framework_TestCase
{
    public function testEncryptDecryptWithAES_256_CBC()
    {
        $arraySettings = [
            'method' => "aes-256-cbc",
            'hash_method' => "sha256",
            'multibyte_key_len' => "8bit",
            'mb_strlen' => "8bit"
        ];

        $secretKey = "testPassword";
        $text = "testword";

        $encryptClass = new Encryption($arraySettings);
        $encryptedWord = $encryptClass->encrypt($text, $secretKey);
        $this->assertEquals($text, $encryptClass->decrypt($encryptedWord, $secretKey));
    }

}
