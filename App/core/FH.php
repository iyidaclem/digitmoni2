<?php
namespace core;

class FH{
 public static function sanitize($dirty){
  return htmlentities($dirty, ENT_QUOTES, 'UTF-8');
 } 

 public static  function inputIsset(array $sanitizedInput, array $expectedInput){
  $keys = array_keys($sanitizedInput);
  $missingInput=array_diff($expectedInput,$keys);
  if(empty($missingInput)) return true;
  $msg=[];
  foreach($missingInput as $k){
    $msg[$k .'_msg'] = $k . ' was not supplied.';
  }
  $res = new Response();
  return $res->SendResponse(400, false, $msg);
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