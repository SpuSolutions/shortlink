<?php
/**
 * Created by PhpStorm.
 * User: salvob
 * Date: 15/05/2016
 * Time: 18:57
 */
//from http://stackoverflow.com/questions/11821195/use-of-initialization-vector-in-openssl-encrypt

$methods = openssl_get_cipher_methods();

var_dump($methods);

$textToEncrypt = "he who doesn't do anything, doesn't go wrong -- Zeev Suraski";
$secretKey = "glop";

echo '<pre>';
foreach ($methods as $method) {
    $encrypted = openssl_encrypt($textToEncrypt, $method, $secretKey);
    $decrypted = openssl_decrypt($encrypted, $method, $secretKey);
    echo $method . ' : ' . $encrypted . ' ; ' . $decrypted . "\n";
}
echo '</pre>';