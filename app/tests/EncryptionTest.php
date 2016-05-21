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

    /**
     *
     */
    public function testCreatingNewEncryptionWithPassword()
    {
        $secretKey = "testPassword";
        $text = "testword";


        $encryptClass = new Encryption($secretKey);
        $encryptedWord = $encryptClass->encrypt($text, $secretKey);
        $this->assertEquals($text, $encryptClass->decrypt($encryptedWord, $secretKey));
    }

}
