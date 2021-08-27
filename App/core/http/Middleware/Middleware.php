<?php 
namespace core\http\Middleware;
use core\Model;
use core\Response;

class Middleware{
  private $char = '0123456789*&%$#@!~?abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

  private $incoming_token;


  public function rand6(){
    return mt_rand(100000,999999); 
  }


  public function token(){
    return $this->generateRandomString();
  }
  public function generateRandomString($length = 50) {
    $charactersLength = strlen($this->char);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $this->char[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }

}