<?php 
namespace core\http\Middleware;

class Middleware{
  private $token="kjfakjakakjklafjlakla;fklakfal";
  private $char = '0123456789*&%$#@!~?abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

  public function authToken(){
    return $this->token;
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