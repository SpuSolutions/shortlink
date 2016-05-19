<?php
/**
 * Created by PhpStorm.
 * User: salvob
 * Date: 15/05/2016
 * Time: 18:57
 */
//from http://stackoverflow.com/questions/11821195/use-of-initialization-vector-in-openssl-encrypt
// and https://gist.github.com/niczak/2501891

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

class Encryption {
    var $skey = "yourSecretKey"; // change this
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
    public  function encode($value){
        if(!$value){return false;}
        $text = $value;
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->skey, $text, MCRYPT_MODE_ECB, $iv);
        return trim($this->safe_b64encode($crypttext));
    }
    public function decode($value){
        if(!$value){return false;}
        $crypttext = $this->safe_b64decode($value);
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->skey, $crypttext, MCRYPT_MODE_ECB, $iv);
        return trim($decrypttext);
    }
}
/*
Sample Call:
$str = "myPassword";
$converter = new Encryption;
$encoded = $converter->encode($str );
$decoded = $converter->decode($encode);    
echo "$encoded<p>$decoded";
*/
?>