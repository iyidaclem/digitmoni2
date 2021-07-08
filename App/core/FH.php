<?php
namespace core;

class FH{
 public static function sanitize($dirty){
  return htmlentities($dirty, ENT_QUOTES, 'UTF-8');
 } 

 public static function arraySanitize($dataArray){
  $sanitized = [];
  $msg =[];
  foreach($dataArray as $k => $v){
    if($k!='acl'){
      $pureVals = FH::sanitize($v);
    $sanitized[$k] = $pureVals;
    }
  }
  return $sanitized;
 }

}