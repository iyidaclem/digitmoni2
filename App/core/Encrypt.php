<?php
namespace core;

class Encrypt{
  private static $ciphering = "AES-128-CTR";
  private static $_iv_length;
  private static $options = 0;
  private static $encryption_iv = '1234567891011121';
  private static $decryption_iv = '1234567891011121';
  private static $enc_key ='digitmoni';
  private static $decrypt_key = 'digitmoni';

  public function __construct(){
    $this->_iv_length = openssl_cipher_iv_length(static::$ciphering);
  }

  public static function __encrypt($originalData){
    $encrypted = openssl_encrypt($originalData, static::$ciphering, static::$enc_key, static::$options, static::$encryption_iv);
    return $encrypted;
  }

  public static function __decrypt($encrypted){
    $decrypted = openssl_decrypt($encrypted, static::$ciphering,static::$decrypt_key, static::$options, static::$decryption_iv);
    return $decrypted;
  }


}