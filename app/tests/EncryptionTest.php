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
        $method = "AES-128-CBC";
        $text = "testword";


        $encryptClass = new Encryption($secretKey);
        $encryptClass->setMethod($method);
        $encryptedWord = $encryptClass->encrypt($text, $secretKey);
        $this->assertEquals($text, $encryptClass->decrypt($encryptedWord, $secretKey));
    }
    /*
Sample Call:
$str = "myPassword";
$converter = new Encryption;
$encoded = $converter->encode($str );
$decoded = $converter->decode($encode);
echo "$encoded<p>$decoded";
*/

}
