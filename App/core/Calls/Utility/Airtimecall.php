<?php 
namespace core\Call\Utility;
use Requests;
use core\Call\CallKeyFromDB;

class AirtimeCall{

  private $httpRequest;
  private $theKey;
  private $ourID;
  public function __construct(){
    $callCredentials = new CallKeyFromDB();
    $cred = $callCredentials->getAPIkeyFromDB();
    $this->theKey = $cred[0];
    $this->ourID = $cred[1];
  }

  public function airtimeTopUpCall($network, $phone,$amount, $user_ref){
    $response = Requests::get("https://mobileairtimeng.com/httpapi/?userid=$this->ourID&pass=$this->theKey&network=$network&phone=$phone&amt=$amount&user_ref=$user_ref");
    return $response;  
  }

}