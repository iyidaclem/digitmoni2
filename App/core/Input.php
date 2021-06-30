<?php
namespace core;

use core\Router;
use core\FH;

class Input{

  public function getRequestMethod(){
    return strtoupper($_SERVER['REQUEST_METHOD']);
  }

  public function isPost(){
    return $this->getRequestMethod() === 'POST';
  }

  public function isGet(){
    return $this->getRequestMethod() === 'GET';
  }

  public function isPut(){
    return $this->getRequestMethod() === 'PUT';
  }

  public function get($input=false) {
    if(!$input){
      // return entire request array and sanitize it
      $data = [];
      foreach($_REQUEST as $field => $value){
        $data[$field] = FH::sanitize($value);
      }
      return $data;
    }
    return FH::sanitize($_REQUEST[$input]);
  }

 
}