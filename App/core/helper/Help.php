<?php
namespace core\Helper;
use core\Response;

class Help{
    
  public function Unique_Id_Gen($for, $digit = null){
    is_null($digit) == false? $length = $digit: $length = 10;
    $result = bin2hex(random_bytes($length));
    return $for.$result;
  }


  public function content_type(){
    if($_SERVER['CONTENT_TYPE'] !== 'application/json'){
      $response = new Response();
      $response->SendResponse(400, false, "Content type header is not set to json");
    }
  }


  public function checkValidJson($rawPostData){
    $jsonData = json_decode($rawPostData);
    if(!$jsonData){
      $response = new Response();
      $response->SendResponse(400, false, "Request body isnt a valid json");
    }else{
      return $jsonData;
    }
  }

  public function dateTimeNow(){
    return date('Y-m-d h:i:s a', time());
  }
 
  function dateDiffInDays($date1, $date2){
    // Calculating the difference in timestamps
    $diff = strtotime($date2) - strtotime($date1);
      
    // 1 day = 24 hours
    // 24 * 60 * 60 = 86400 seconds
    return abs(round($diff / 86400));
}
  

}