<?php 
use core\Input;
use core\Response;
$response = new Response();

function Unique_Id_Gen($for, $digit = null){
  is_null($digit) == false? $length = $digit: $length = 10;
  $result = bin2hex(random_bytes($length));
  return $for.$result;
}


function issset_accesToken(){
  if(!isset($_SERVER['HTTP_AUTHORIZATION']) || strlen($_SERVER['HTTP_AUTHORIZATION']) <1){
    $response = new Response();
    $response->SendResponse(401, false, "Access token is either blank or not provided.");
  }
}


function content_type(){
  if($_SERVER['CONTENT_TYPE'] !== 'application/json'){
    $response = new Response();
    $response->SendResponse(400, false, "Content type header is not set to json");
  }
}


function checkValidJson($rawPostData){
  $jsonData = json_decode($rawPostData);
  if(!$jsonData){
    $response = new Response();
    $response->SendResponse(400, false, "Request body isnt a valid json");
  }else{
    return $jsonData;
  }
}


function date_time(){
  $today = date("Y-m-d H:i:s");
  return $today;
}


function check_key($http_array_key){
  if(!array_key_exists($http_array_key, $_GET)){
    $response = new Response();
    $response->SendResponse(401, false, 'Now query params found in URL.');
   }
   $val = $_GET[$http_array_key]; 
  
   if($val == ''){
    $response = new Response();
    $response->SendResponse(400, false, 'Empty query key.');
  }
}