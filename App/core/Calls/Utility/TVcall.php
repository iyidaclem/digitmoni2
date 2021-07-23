<?php 
namespace core\Call\Utility;

class TVcalls{

  public function validateTVUser($userid,$bill,$pass, $cardNo){
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://mobileairtimeng.com/httpapi/customercheck?userid=$userid&pass=$pass&bill=$bill&smartno=$cardNo",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      // CURLOPT_HTTPHEADER => array(
      //   "Authorization: Basic MGZiYTgwYmY0MGZkOTMxOmYyMDNlZjdkMGY3NmE5OA=="
      // ),
    ));
    $response = json_decode(curl_exec($curl));
    $err = curl_error($curl);
    curl_close($curl);
    if($err)return false;
    return $response;
  }
  
  
  public function starTimeCall(){

  }

  public function dstv(){

  }

  public function gotv(){

  }
}