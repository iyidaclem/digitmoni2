<?php 
namespace core\Call\Utility;
use Requests;
use core\Call\CallKeyFromDB;

class TVcalls{

  
  private $httpRequest;
  private $theKey;
  private $ourID;
  public function __construct(){
    $callCredentials = new CallKeyFromDB();
    $cred = $callCredentials->getAPIkeyFromDB();
    $this->theKey = $cred[0];
    $this->ourID = $cred[1];
  }


  public function validateTVUser($userid,$bill,$pass, $cardNo){
   
    return $response;
  }
  
  
  public function starTimeCall(){

  }

  public function dstv(){

  }

  public function gotv(){

  }
}